<?PHP
    if (!isset($_SESSION['sessunameetilang'])) {
        header('location:index.php');
    }
?>

<nav class="side-menu">
    <div class="side-menu-avatar">
        <div class="avatar-preview avatar-preview-100">
            <img src="img/avatar-1-256.png" alt="">
        </div>
        <center>
            <span class="lbl"><?PHP echo $_SESSION['sessnameetilang'];?></span><br>
        </center>
    </div>
    <ul class="side-menu-list">
        <li class="brown">
            <a href="home.php">
                <i class="font-icon glyphicon glyphicon-home"></i>
                <span class="lbl">Home</span>
            </a>
        </li>
        <!kalo menunya lagi open kasih atribut opened-->
        <li class="blue-dirty">
            <a href="pelanggaran_overview.php">
                <i class="font-icon glyphicon glyphicon-th"></i>
                <span class='lbl'>Daftar Pelanggaran</span>
            </a>
        </li>
        <!--<li class="blue-dirty">
            <a href="pelanggaran_personil_overview.php">
                <i class="font-icon glyphicon glyphicon-th"></i>
                <span class='lbl'>Daftar Pelanggaran Personil</span>
            </a>
        </li>-->
        <li class="blue-dirty">
            <a href="blacklist_overview.php">
                <i class="font-icon glyphicon glyphicon-th"></i>
                <span class='lbl'>Daftar Blacklist</span>
            </a>
        </li>
        <li class="blue-dirty with-sub">
            <span>
                <i class="font-icon glyphicon glyphicon-th"></i>
                <span class="lbl">Master Data</span>
            </span>
            <ul>   
                <li class="blue-dirty">
                    <a href="m_penanggungjawab.php">
                        <span class='lbl'>Penanggungjawab</span>
                    </a>
                </li>
                <li class="blue-dirty">
                    <a href="m_pelanggaran.php">
                        <span class='lbl'>Pelanggaran</span>
                    </a>
                </li>
                <li class="blue-dirty">
                    <a href="m_lokasi.php">
                        <span class='lbl'>Lokasi</span>
                    </a>
                </li>
                <li class="blue-dirty">
                    <a href="m_pelanggar.php">
                        <span class='lbl'>Pelanggar</span>
                    </a>
                </li>
                <li class="blue-dirty">
                    <a href="m_kendaraan.php">
                        <span class='lbl'>Kendaraan</span>
                    </a>
                </li>
                <!-- <li class="with-sub">
                    <span>
                        <span class="lbl">Level 2</span>
                    </span>
                    <ul>
                        <li><a href="#"><span class="lbl">Level 2</span></a></li>
                        <li><a href="#"><span class="lbl">Level 2</span></a></li>
                        <li class="with-sub">
                    </ul>
                </li> -->
            </ul>
        </li>
        <li class="red">
            <a href="logout.php">
                <i class="font-icon glyphicon glyphicon-log-out"></i>
                <span class="lbl">Logout</span>
            </a>
        </li>
    </ul>
</nav><!--.side-menu-->