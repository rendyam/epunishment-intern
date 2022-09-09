<?PHP
    $baseURL = '';

    if($_SERVER['SERVER_NAME']=='cigading.ptkbs.co.id'){
        $baseURL = 'http://cigading.ptkbs.co.id:8086';
    }elseif($_SERVER['SERVER_NAME']=='115.85.65.178'){
        $baseURL = 'http://115.85.65.178:8086';
    }elseif($_SERVER['SERVER_NAME']=='localhost'){
        $baseURL = 'http://localhost:88';
    }elseif($_SERVER['SERVER_NAME']=='127.0.0.1'){
        $baseURL = 'http://127.0.0.1:88';
    }else{
        $baseURL = 'http://192.168.0.27:88';
    }
?>

            <section>
	        <header class="side-menu-title">Daftar Aplikasi</header>
	        <ul class="side-menu-list">
	            <li>
	                <a href="<?PHP echo $baseURL?>/efile">
	                    <i class="tag-color green"></i>
	                    <span class="lbl">E-Letter</span>
	                </a>
	            </li>
	            <!--<li>
	                <a href="<?PHP //echo $live_server?>/emeeting">
	                    <i class="tag-color grey-blue"></i>
	                    <span class="lbl">E-Meeting</span>
	                </a>
	            </li>-->
	            <li>
                        <a href="<?PHP echo $baseURL?>/uangmuka">
	                    <i class="tag-color red"></i>
	                    <span class="lbl">Uang Muka</span>
	                </a>
	            </li>
                    <?PHP
                        if($_SESSION['sessmansetdatamaster']==1){
                            echo "<li>
                                    <a href='$baseURL/manset'>
                                        <i class='tag-color blue-dirty'></i>
                                        <span class='lbl'>Aset Tetap</span>
                                    </a>
                                </li>";
                        }
                    ?>
                    <?PHP
                        if($_SESSION['sessbttdro']==1 || $_SESSION['sessbttdadmin']==1 || $_SESSION['sessbttdapproval']==1){
                            echo "<li>
                                    <a href='$baseURL/bttd'>
                                        <i class='tag-color grey-blue'></i>
                                        <span class='lbl'>BTTD</span>
                                    </a>
                                </li>";
                        }
                    ?>
	        </ul>
	    </section>