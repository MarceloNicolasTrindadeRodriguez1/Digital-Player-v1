<?php
session_start();

$categories = [];
$channels = [];

function addChannelsToCategories(&$categoriesArray, $channelsArray) {
    foreach ($channelsArray as $channel) {
        $category_id = $channel['category_id'];
        if (isset($categoriesArray[$category_id])) {
            $categoriesArray[$category_id]['channels'][] = $channel;
        }
    }
}

function transformCategories($categories_list) {
    $server = $_SESSION['user']['server'];
    $username = $_SESSION['user']['username'];
    $password = $_SESSION['user']['password'];
    $result = [];
    
    foreach ($categories_list as $category) {
        $category_id = $category['category_id'];
        $category_name = $category['category_name'];
        $result[$category_id] = [
            'category_name' => $category_name,
            'channels' => []
        ];
    }
    
    $get_live_streams_url = "$server/player_api.php?username=$username&password=$password&action=get_live_streams";
    $response = file_get_contents($get_live_streams_url);
    if ($response !== false) {
        $list = json_decode($response, true);
        addChannelsToCategories($result, $list['data']);
    }
    
    return $result; // Return the transformed categories
}

if (!isset($_SESSION['user']) && !isset($_SESSION['m3u_link'])) {
    header("Location: index.php");
    exit();
}

if (isset($_SESSION['user'])) {
    // Fetch data from Xtream Codes API
    $server = $_SESSION['user']['server'];
    $username = $_SESSION['user']['username'];
    $password = $_SESSION['user']['password'];
    $api_url = "$server/player_api.php?username=$username&password=$password&action=get_live_categories";

    $response = file_get_contents($api_url);
    if ($response === FALSE) {
        echo "<h1>Failed to fetch data: Unable to connect to API</h1>";
        exit();
    }
    
    $categories = transformCategories(json_decode($response, true)); // Assign transformed categories
}

// Uncomment the following block if you plan to handle M3U links
/*
elseif (isset($_SESSION['m3u_link'])) {
    // Fetch and parse M3U data
    $m3u_link = $_SESSION['m3u_link'];
    $handle = fopen($m3u_link, "r");
    if ($handle === FALSE) {
        echo "<h1>Failed to fetch data: Unable to connect to M3U link</h1>";
        exit();
    }

    $currentCategory = 'Uncategorized';
    while (($line = fgets($handle)) !== false) {
        $line = trim($line);
        if (strpos($line, '#EXTINF:') === 0) {
            preg_match('/#EXTINF:-1,(.+)$/', $line, $matches);
            $name = $matches[1] ?? 'Unknown';
            $channels[$currentCategory][] = ['name' => $name, 'logo' => '', 'url' => ''];
        } elseif (strpos($line, 'http') === 0) {
            $channels[$currentCategory][count($channels[$currentCategory]) - 1]['url'] = $line;
        }
    }
    fclose($handle);
    $categories = array_keys($channels);
}
*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live TV | Digital Player</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/live_tv.css">
</head>
<body>
    <div class="container-fluid py-3">
        <h1 class="text-center mb-4">Live TV</h1>
        <div class="row g-3">
            <!-- Categories Section -->
            <div class="col-md-3">
                <div class="categories">
                    <h4>Categories</h4>
                    <ul class="list-group">
                        <?php foreach ($categories as $category_id => $category): ?>
                            <li class="list-group-item category-item" onclick="loadChannels('<?php echo $category_id; ?>')">
                                <?php echo htmlspecialchars($category['category_name']); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <!-- Channels Section -->
            <div class="col-md-5">
                <div class="channels">
                    <h4>Channels</h4>
                    <div id="channelList" class="d-flex flex-wrap gap-3">
                        <p>Select a category to load channels...</p>
                    </div>
                </div>
            </div>
            <!-- Live Stream Section -->
            <div class="col-md-4">
                <div class="live-stream">
                    <video id="liveStreamPlayer" controls autoplay>
                        <source id="videoSource" src="" type="application/x-mpegURL">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const channels = <?php echo json_encode($channels); ?>;

        function loadChannels(category) {
            const channelList = document.getElementById('channelList');
            channelList.innerHTML = '';

            if (channels[category]) {
                channels[category].forEach(channel => {
                    const channelDiv = document.createElement('div');
                    channelDiv.className = 'channel-item';
                    channelDiv.innerHTML = `
                        <img src="${channel.logo}" alt="${channel.name}" class="channel-logo">
                        <p>${channel.name}</p>
                    `;
                    channelDiv.onclick = () => {
                        document.getElementById('videoSource').src = channel.url;
                        document.getElementById('liveStreamPlayer').load();
                    };
                    channelList.appendChild(channelDiv);
                });
            } else {
                channelList.innerHTML = '<p>No channels available for this category.</p>';
            }
        }
    </script>
</body>
</html>
