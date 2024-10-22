<?php
include('../assets/inc/lang.php');
include('../website.conf.php');
include('../assets/inc/utils.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('../assets/inc/global_head.php'); ?>
    <title><?php echo $config['title'] ?></title>
    <meta property="og:title" content="<?php echo $config['longer_description'] ?>">
    <meta property="og:image" content="<?php echo return_url($config['profile_picture']) ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo $config['rooturl'] ?>assets/css/dashboard.css">
    
</head>
<body>
<?php include('../assets/inc/nav.php') ?>
<nav style="background-color: <?php echo $config['sub_accent_color'] ?>">
        <div class="container">
            <div class="nav-wrapper">
                <div class="col s12">
                    <a href="<?php echo $config['langurl'] ?>" class="breadcrumb"><?php echo $config['long_title'] ?></a>
                    <a href="" class="breadcrumb">Dashboard</a>
                </div>
            </div>
        </div>
    </nav>

<div class="container">
    <div class="container" style="text-align : center">
        <a href="<?php echo $config['rooturl']?>admin/generate_sitemap" >Generate Sitemap</a>
    </div>
    <form method="GET" action="">
        <label for="time_scale" class="theme-font-color">Time Scale:</label>
        <select name="time_scale" id="time_scale" onchange="this.form.submit()">
            <option value="day" <?php if(isset($_GET['time_scale']) && $_GET['time_scale'] == 'day') echo 'selected'; ?>>Day</option>
            <option value="week" <?php if(isset($_GET['time_scale']) && $_GET['time_scale'] == 'week') echo 'selected'; ?>>Week</option>
            <option value="month" <?php if(!isset($_GET['time_scale']) || $_GET['time_scale'] == 'month') echo 'selected'; ?>>Month</option>
            <option value="year" <?php if(isset($_GET['time_scale']) && $_GET['time_scale'] == 'year') echo 'selected'; ?>>Year</option>
        </select>
    </form>
</div>

<?php

function print_stats($time_scale){
    global $config;
    try{
        $db = new PDO('mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'] . ';charset=utf8', $config['db_user'], $config['db_password']);
    }
    catch(Exception $e){
        die('Database error.');
    }

    // Determine SQL date format based on the selected time scale
    switch($time_scale) {
        case 'day':
            $date_format = '%Y-%m-%d';
            $interval = 'P1D';
            $php_date_format = 'Y-m-d';
            break;
        case 'week':
            $date_format = '%Y-%u'; // %u is the week number
            $interval = 'P1W';
            $php_date_format = 'Y-W';
            break;
        case 'month':
            $date_format = '%Y-%m';
            $interval = 'P1M';
            $php_date_format = 'Y-m';
            break;
        case 'year':
            $date_format = '%Y';
            $interval = 'P1Y';
            $php_date_format = 'Y';
            break;
        default:
            $date_format = '%Y-%m';
            $interval = 'P1M';
            $php_date_format = 'Y-m';
    }

     // Retrieve visit and unique visitor statistics for the entire history based on the selected time scale
     $req = $db->prepare("
     SELECT DATE_FORMAT(use_date, '$date_format') as period, 
            COUNT(*) as visits, 
            COUNT(DISTINCT session) as unique_visitors 
     FROM stats 
     GROUP BY period 
     ORDER BY period DESC
 ");
 $req->execute();
 $stats = $req->fetchAll(PDO::FETCH_ASSOC);
 $req->closeCursor();
 
 $periods = [];
 $visits = [];
 $unique_visitors = [];
 foreach ($stats as $stat) {
     $periods[] = $stat['period'];
     $visits[] = $stat['visits'];
     $unique_visitors[] = $stat['unique_visitors'];
 }

 // Use Chart.js for the graph
 echo '<h2 class="theme-font-color">Visits Evolution (' . htmlspecialchars($time_scale) . ')</h2>';
 echo '<canvas id="visitsChart"></canvas>';
 echo '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
 echo '<script>
 const ctx = document.getElementById("visitsChart").getContext("2d");
 const visitsChart = new Chart(ctx, {
     type: "line",
     data: {
         labels: ' . json_encode($periods) . '.reverse(),
         datasets: [{
             label: "Pages Visited",
             data: ' . json_encode($visits) . '.reverse(),
             borderColor: "rgba(75, 192, 192, 1)",
             borderWidth: 1
         },
         {
             label: "Unique Visitors",
             data: ' . json_encode($unique_visitors) . '.reverse(),
             borderColor: "rgba(153, 102, 255, 1)",
             borderWidth: 1
         }]
     },
     options: {
         scales: {
             y: {
                 beginAtZero: true
             }
         }
     }
 });
 </script>';


    // Retrieve page visits for the selected time scale
    $req = $db->prepare("
        SELECT endpoint, lang, COUNT(*) as visits 
        FROM stats 
        WHERE DATE_FORMAT(use_date, '$date_format') = DATE_FORMAT(CURRENT_DATE(), '$date_format')
        GROUP BY endpoint, lang
    ");
    $req->execute();
    $raw_pages = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();

    // Organize the data
    $pages = [];
    foreach ($raw_pages as $page) {
        if (!isset($pages[$page['endpoint']])) {
            $pages[$page['endpoint']] = [
                'total_visits' => 0,
                'langs' => []
            ];
        }
        $pages[$page['endpoint']]['total_visits'] += $page['visits'];
        $pages[$page['endpoint']]['langs'][] = [
            'lang' => $page['lang'],
            'visits' => $page['visits']
        ];
    }

    // Sort pages by total visits and limit to top 10
    uasort($pages, function($a, $b) {
        return $b['total_visits'] - $a['total_visits'];
    });
    $pages = array_slice($pages, 0, 10); // Limit to top 10 pages

    if ($pages) {
        echo '<h2 class="theme-font-color">Pages Visited (' . htmlspecialchars($time_scale) . ')</h2>';
        echo '<table>';
        echo '<tr><th class="theme-font-color">Page</th><th class="theme-font-color">Total Visits</th><th class="theme-font-color">Details by Language</th></tr>';
        foreach ($pages as $endpoint => $data) {
            echo '<tr>';
            echo '<td class="theme-font-color">' . $endpoint . '</td>';
            echo '<td class="theme-font-color">' . $data['total_visits'] . '</td>';
            echo '<td class="theme-font-color">';
            foreach ($data['langs'] as $lang) {
                echo '<p class="theme-font-color">' . $lang['lang'] . ': ' . $lang['visits'] . '</p>';
            }
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }

    // Retrieve unique users for each period
    $req = $db->prepare("
        SELECT DATE_FORMAT(use_date, '$date_format') as period, 
               COUNT(DISTINCT session) as unique_users 
        FROM stats 
        GROUP BY period 
        ORDER BY period DESC
        LIMIT 10
    ");
    $req->execute();
    $stats = $req->fetchAll(PDO::FETCH_ASSOC);
    $req->closeCursor();

    // Generate missing periods
    $periods = [];
    $start_date = new DateTime();
    $start_date->modify('-6 months'); // Adjust as needed
    $end_date = new DateTime();

    while ($start_date <= $end_date) {
        $periods[$start_date->format($php_date_format)] = 0;
        $start_date->add(new DateInterval($interval));
    }

    foreach ($stats as $stat) {
        $periods[$stat['period']] = $stat['unique_users'];
    }

    // Display unique users per period, limited to top 10
    echo '<h2 class="theme-font-color">Unique Users (' . htmlspecialchars($time_scale) . ')</h2>';
    echo '<table>';
    echo '<tr><th class="theme-font-color">Period</th><th class="theme-font-color">Number of Unique Users</th></tr>';
    
    $top_periods = array_slice(array_reverse($periods), 0, 10); // Limit to top 10 periods
    foreach ($top_periods as $period => $unique_users) {
        echo '<tr>';
        echo '<td class="theme-font-color">' . $period . '</td>';
        echo '<td class="theme-font-color">' . $unique_users . '</td>';
        echo '</tr>';
    }
    echo '</table>';

   
    // Raw data table limited to 50 records
    $req = $db->prepare("
        SELECT use_date, client_info, session, lang, endpoint 
        FROM stats 
        WHERE DATE_FORMAT(use_date, '$date_format') = DATE_FORMAT(CURRENT_DATE(), '$date_format')
        ORDER BY use_date DESC LIMIT 50
    ");
    $req->execute();
    
    echo '<h2 class="theme-font-color">Recent Activities</h2>';
    echo '<table>';
    echo '<tr><th class="theme-font-color">Usage Date</th><th class="theme-font-color">Client Info</th><th class="theme-font-color">Session</th><th class="theme-font-color">Language</th><th class="theme-font-color">Endpoint</th></tr>';
    while($data = $req->fetch()){
        echo '<tr>';
        echo '<td class="theme-font-color">' . $data['use_date'] . '</td>';
        echo '<td class="theme-font-color">' . $data['client_info'] . '</td>';
        echo '<td class="theme-font-color">' . $data['session'] . '</td>';
        echo '<td class="theme-font-color">' . $data['lang'] . '</td>';
        echo '<td class="theme-font-color">' . $data['endpoint'] . '</td>';
        echo '</tr>';
    }
    echo '</table>';
    $req->closeCursor();
}

$time_scale = isset($_GET['time_scale']) ? $_GET['time_scale'] : 'month';

?>
<div class="container">
    <div class="col">
        <?php print_stats($time_scale); ?>
    </div>
</div>

<?php include('../assets/inc/footer.php'); ?> 
<?php include('../assets/inc/global_js.php'); ?>
</body>
</html>
