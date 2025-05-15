<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>

<body>
    <?php
    include_once 'config.php';
    session_start();
    if (!(isset($_SESSION['email']))) {
        header("location:index.php");
    } else {
        $name = $_SESSION['username'];
        $email = $_SESSION['email'];
        include_once 'config.php';
    }
    ?>
    <div class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">

                    <div class="navbar-header">
                        <button class="navbar-toggle" data-target="#mobile_menu" data-toggle="collapse"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
                        <a href="#" class="navbar-brand">ONLINE-EXAM</a>
                    </div>

                    <div class="navbar-collapse collapse" id="mobile_menu">
                        <ul class="nav navbar-nav">
                            <li <?php if (@$_GET['q'] == 1) echo 'class="active"'; ?>><a href="landingpage.php?q=1"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>&nbsp;Home<span class="sr-only">(current)</span></a></li>
                            <li <?php if (@$_GET['q'] == 2) echo 'class="active"'; ?>><a href="landingpage.php?q=2"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>&nbsp;History</a></li>
                            <li <?php if (@$_GET['q'] == 3) echo 'class="active"'; ?>><a href="landingpage.php?q=3"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span>&nbsp;Ranking</a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-log-in"></span>Signout<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li> <a href="logout.php?q=landingpage.php"><span class="glyphicon glyphicon-log-out" aria-hidden="true"> Signout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!--home start-->
    <?php if (@$_GET['q'] == 1) {

        $result = mysqli_query($con, "SELECT * FROM quiz ORDER BY date DESC") or die('Error');
        echo  '<div class="panel"><div class="table-responsive"><table class="table table-striped title1">
<tr><td><b>S.N.</b></td><td><b>Topic</b></td><td><b>Total question</b></td><td><b>Marks</b></td><td><b>Time limit</b></td><td></td></tr>';
        $c = 1;
        while ($row = mysqli_fetch_array($result)) {
            $title = $row['title'];
            $total = $row['total'];
            $sahi = $row['sahi'];
            $time = $row['time'];
            $eid = $row['eid'];
            $q12 = mysqli_query($con, "SELECT score FROM history WHERE eid='$eid' AND email='$email'") or die('Error98');
            $rowcount = mysqli_num_rows($q12);
            if ($rowcount == 0) {
                echo '<tr><td>' . $c++ . '</td><td>' . $title . '</td><td>' . $total . '</td><td>' . $sahi * $total . '</td><td>' . $time . '&nbsp;min</td>
	<td><b><a href="landingpage.php?q=quiz&step=2&eid=' . $eid . '&n=1&t=' . $total . '" class="pull-right btn sub1" style="margin:0px;background:#99cc32"><span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>&nbsp;<span class="title1"><b>Start</b></span></a></b></td></tr>';
            } else {
                echo '<tr style="color:#99cc32"><td>' . $c++ . '</td><td>' . $title . '&nbsp;<span title="This quiz is already solve by you" class="glyphicon glyphicon-ok" aria-hidden="true"></span></td><td>' . $total . '</td><td>' . $sahi * $total . '</td><td>' . $time . '&nbsp;min</td>
	<td><b><a href="update.php?q=quizre&step=25&eid=' . $eid . '&n=1&t=' . $total . '" class="pull-right btn sub1" style="margin:0px;background:red"><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span>&nbsp;<span class="title1"><b>Restart</b></span></a></b></td></tr>';
            }
        }
        $c = 0;
        echo '</table></div></div>';
    } ?>


    <!--home closed-->

    <!--quiz start-->
    <?php
    if (@$_GET['q'] == 'quiz' && @$_GET['step'] == 2) {
        $eid = @$_GET['eid'];
        $sn = @$_GET['n'];
        $total = @$_GET['t'];
        $q = mysqli_query($con, "SELECT * FROM questions WHERE eid='$eid' AND sn='$sn' ");
        echo '<div class="panel" style="margin:5%">';
        while ($row = mysqli_fetch_array($q)) {
            $qns = $row['qns'];
            $qid = $row['qid'];
            echo '<b>Question &nbsp;' . $sn . '&nbsp;::<br />' . $qns . '</b><br /><br />';
        }
        $q = mysqli_query($con, "SELECT * FROM options WHERE qid='$qid' ");
        echo '<form action="update.php?q=quiz&step=2&eid=' . $eid . '&n=' . $sn . '&t=' . $total . '&qid=' . $qid . '" method="POST"  class="form-horizontal">
<br />';

        while ($row = mysqli_fetch_array($q)) {
            $option = $row['option'];
            $optionid = $row['optionid'];
            echo '<input type="radio" name="ans" value="' . $optionid . '">' . $option . '<br /><br />';
        }
        echo '<br /><button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span>&nbsp;Submit</button></form></div>';
        //header("location:dash.php?q=4&step=2&eid=$id&n=$total");
    }
    //result display
    if (@$_GET['q'] == 'result' && @$_GET['eid']) {
        $eid = @$_GET['eid'];
        $q = mysqli_query($con, "SELECT * FROM history WHERE eid='$eid' AND email='$email' ") or die('Error157');
        echo  '<div class="panel">
<center><h1 class="title" style="color:#660033">Result</h1><center><br /><table class="table table-striped title1" style="font-size:20px;font-weight:1000;">';

        while ($row = mysqli_fetch_array($q)) {
            $s = $row['score'];
            $w = $row['wrong'];
            $r = $row['sahi'];
            $qa = $row['level'];
            echo '<tr style="color:#66CCFF"><td>Total Questions</td><td>' . $qa . '</td></tr>
      <tr style="color:#99cc32"><td>right Answer&nbsp;<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span></td><td>' . $r . '</td></tr> 
	  <tr style="color:red"><td>Wrong Answer&nbsp;<span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></td><td>' . $w . '</td></tr>
	  <tr style="color:#66CCFF"><td>Score&nbsp;<span class="glyphicon glyphicon-star" aria-hidden="true"></span></td><td>' . $s . '</td></tr>';
        }
        $q = mysqli_query($con, "SELECT * FROM rank WHERE  email='$email' ") or die('Error157');
        while ($row = mysqli_fetch_array($q)) {
            $s = $row['score'];
            echo '<tr style="color:#990000"><td>Overall Score&nbsp;<span class="glyphicon glyphicon-stats" aria-hidden="true"></span></td><td>' . $s . '</td></tr>';
        }
        echo '</table></div>';
    }
    ?>
    <!--quiz end-->
    <?php
    //history start
    if (@$_GET['q'] == 2) {
        $q = mysqli_query($con, "SELECT * FROM history WHERE email='$email' ORDER BY date DESC ") or die('Error197');
        echo  '<div class="panel title">
<table class="table table-striped title1" >
<tr style="color:red"><td><b>S.N.</b></td><td><b>Quiz</b></td><td><b>Question Solved</b></td><td><b>Right</b></td><td><b>Wrong<b></td><td><b>Score</b></td>';
        $c = 0;
        while ($row = mysqli_fetch_array($q)) {
            $eid = $row['eid'];
            $s = $row['score'];
            $w = $row['wrong'];
            $r = $row['sahi'];
            $qa = $row['level'];
            $q23 = mysqli_query($con, "SELECT title FROM quiz WHERE  eid='$eid' ") or die('Error208');
            while ($row = mysqli_fetch_array($q23)) {
                $title = $row['title'];
            }
            $c++;
            echo '<tr><td>' . $c . '</td><td>' . $title . '</td><td>' . $qa . '</td><td>' . $r . '</td><td>' . $w . '</td><td>' . $s . '</td></tr>';
        }
        echo '</table></div>';
    }

    //ranking start
    if (@$_GET['q'] == 3) {
        $q = mysqli_query($con, "SELECT * FROM rank  ORDER BY score DESC ") or die('Error223');
        echo  '<div class="panel title"><div class="table-responsive">
<table class="table table-striped title1" >
<tr style="color:red"><td><b>Rank</b></td><td><b>Name</b></td><td><b>Score</b></td></tr>';
        $c = 0;
        while ($row = mysqli_fetch_array($q)) {
            $e = $row['email'];
            $s = $row['score'];
            $q12 = mysqli_query($con, "SELECT * FROM user WHERE email='$e' ") or die('Error231');
            while ($row = mysqli_fetch_array($q12)) {
                $name = $row['name'];
            }
            $c++;
            echo '<tr><td style="color:#99cc32"><b>' . $c . '</b></td><td>' . $name . '</td><td>' . $s . '</td><td>';
        }
        echo '</table></div></div>';
    }
    ?>
</body>
</html>