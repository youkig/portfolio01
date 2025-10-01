<div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="index.php" class="site_title"><span>メタ自動更新プログラム</span></a>
            </div>

            <div class="clearfix"></div>

<!-- menu profile quick info -->
<div class="profile clearfix">
              
              <div class="profile_info">
                <span>Welcome,</span>
                <h2><?=$_SESSION['username'];?></h2>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br /> 
 
 <!-- sidebar menu -->
 <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="index.php">Dashboard</a></li>
                      
                    </ul>
                  </li>
                  <li><a><i class="fa fa-edit"></i> サイト登録 <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="siteregist.php">新規サイト登録</a></li>
                      <li><a href="sitelist.php">登録サイト一覧</a></li>
                    </ul>
                  </li>

                  
                  
                </ul>
              </div>
             

            </div>
            <!-- /sidebar menu -->

            </div>
        </div>   