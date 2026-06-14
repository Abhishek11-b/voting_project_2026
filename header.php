<?php
?>
<style>
body {
    margin:0;
    font-family: 'Segoe UI', sans-serif;
}

.header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:12px 25px;
    background:linear-gradient(90deg,#0f172a,#1e3a8a);
    color:white;
}

.logo-box {
    display:flex;
    align-items:center;
    gap:10px;
}

.logo-box img {
    height:45px;
}

.title {
    font-size:18px;
    font-weight:bold;
}

.subtitle {
    font-size:12px;
    opacity:0.8;
}
</style>

<div class="header">

    <div class="logo-box">
        <img src="logo.png">
        <div>
            <div class="title">Prudence International Residential School</div>
            <div class="subtitle">Voting System</div>
        </div>
    </div>

    <div>
        <?php if(isset($_SESSION['student_name'])){ ?>
            Welcome, <?php echo $_SESSION['student_name']; ?>
        <?php } else { ?>
            Welcome, Admin
        <?php } ?>
    </div>

</div>