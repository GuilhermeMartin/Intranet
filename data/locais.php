<?php
require_once("../assets/php/class/class.seg.php");
session_start();
proteger();

$host="10.0.0.2";
$service="//10.0.0.2:1521/orcl";
$id=$_SESSION['usuarioId'];
$conn= new \PDO("oci:host=$host;dbname=$service","INTRANET","ifnefy6b9");

$query1 = "SELECT USR.EMAIL, USR.TIPO_USUARIO, USR.SETOR, USR.IMG_PERFIL, IMG.IMAGEM,
    CASE
      WHEN USR.SETOR IN (SELECT SIGLA FROM IN_SETORES SETO, IN_MURAL MUR WHERE MUR.SETOR = SETO.SIGLA)
      THEN 'S'
      ELSE 'N'
      END AS MURAL,
    CASE
      WHEN USR.ID IN (SELECT GESTOR FROM IN_SETORES WHERE GESTOR = :id)
      THEN 'S'
      ELSE 'N'
      END AS GESTOR
FROM 
    IN_USUARIOS USR, 
    IN_IMAGENS IMG 
WHERE 
    USR.IMG_PERFIL = IMG.ID AND USR.ID =:id";
$query2 = "SELECT * FROM IN_LOCAIS ORDER BY 2";

//#1
$stmt1 = $conn->prepare($query1);
$stmt1->bindValue(':id',$id);
$stmt1->execute();
$result1=$stmt1->fetch(PDO::FETCH_ASSOC);

//#2
$stmt2 = $conn->prepare($query2);
$stmt2->execute();
$result2=$stmt2->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Aniger - Dados - Locais</title>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- BEGIN PLUGIN CSS -->
    <link href="../assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="../assets/plugins/bootstrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/bootstrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/animate.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/jquery-datatable/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
    <link href="../assets/plugins/datatables-responsive/css/datatables.responsive.css" rel="stylesheet" type="text/css" media="screen" />
    <!-- <link href="../assets/plugins/jquery-scrollbar/jquery.scrollbar.css" rel="stylesheet" type="text/css" /> -->
    <!-- END PLUGIN CSS -->
    <!-- BEGIN CORE CSS FRAMEWORK -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="../webarch/css/webarch.css" rel="stylesheet" type="text/css" />
    <!-- END CORE CSS FRAMEWORK -->
    <link rel="shortcut icon" href="../assets/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="../assets/img/favicon.ico" type="image/x-icon">
  </head>
  <body class="">
    <!-- BEGIN HEADER -->
    <div class="header navbar navbar-inverse ">
      <!-- BEGIN TOP NAVIGATION BAR -->
      <div class="navbar-inner">
        <div class="header-seperation">
          <ul class="nav pull-left notifcation-center visible-xs visible-sm">
            <li class="dropdown">
              <a href="#main-menu" data-webarch="toggle-left-side">
                <i class="material-icons">menu</i>
              </a>
            </li>
          </ul>
          <!-- BEGIN LOGO -->
          <a href="../index.php">
            <img src="../assets/img/logo.png" class="logo" alt="" width="106" height="21" />
          </a>
          <!-- END LOGO -->
          <ul class="nav pull-right notifcation-center">
            <li class="dropdown hidden-xs hidden-sm">
              <a href="../index.php" class="dropdown-toggle active" data-toggle="">
                <i class="material-icons">home</i>
              </a>
            </li>
            <li class="dropdown hidden-xs hidden-sm">
              <a href="../chamados.php" class="dropdown-toggle">
                <i class="material-icons">desktop_mac</i><!-- <span class="badge bubble-only"></span> -->
              </a>
            </li>
            <!--<li class="dropdown visible-xs visible-sm">
              <a href="#" data-webarch="toggle-right-side">
                <i class="material-icons">chat</i>
              </a>
            </li>-->
          </ul>
        </div>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <div class="header-quick-nav">
          <!-- BEGIN TOP NAVIGATION MENU -->
          <div class="pull-left">
            <ul class="nav quick-section">
              <li class="quicklinks">
                <a href="#" class="" id="layout-condensed-toggle">
                  <i class="material-icons">menu</i>
                </a>
              </li>
            </ul>
            <ul class="nav quick-section">
              <li class="quicklinks  m-r-10">
                <a href="javascript:history.go(0)" class="">
                  <i class="material-icons">refresh</i>
                </a>
              </li>
              <li class="quicklinks">
                <a href="#" class="" id="my-task-list" data-placement="bottom" data-content='' data-toggle="dropdown" data-original-title="Novidades">
                  <i class="material-icons">notifications_none</i>
                  <span class="badge badge-important bubble-on  ly"></span>
                </a>
              </li>
              <li class="quicklinks"> <span class="h-seperate"></span></li>
              <?php
                if ($result1['TIPO_USUARIO'] == 'ADM') {
                  echo '
                  <li class="quicklinks">
                    <a href="../dados.php">
                      <i class="material-icons">apps</i>
                    </a>
                  </li>';
                } elseif ($result1['MURAL'] == 'S') {
                  echo '
                  <li class="quicklinks">
                    <a href="../dados.php">
                      <i class="material-icons">apps</i>
                    </a>
                  </li>';
                } elseif ($result1['GESTOR'] == 'S') {
                  echo '
                  <li class="quicklinks">
                    <a href="../dados.php">
                      <i class="material-icons">apps</i>
                    </a>
                  </li>';
                } elseif ($result1['SETOR'] == 'RH' || $result1['SETOR'] == 'REC') {
                  echo '
                  <li class="quicklinks">
                    <a href="../dados.php">
                      <i class="material-icons">apps</i>
                    </a>
                  </li>';
                }                  
              ?>
              <!--<li class="m-r-10 input-prepend inside search-form no-boarder">
                <span class="add-on"> <i class="material-icons">search</i></span>
                <input name="" type="text" class="no-boarder " placeholder="Buscar" style="width:250px;">
              </li>-->
            </ul>
          </div>
          <div id="notification-list" style="display:none">
            <div style="width:220px">
            <a href="../changelog.php">
              <div class="notification-messages info">
                <div class="user-profile">
                  <img src="../assets/img/profiles/Aa.jpg" width="35" height="35">
                </div>
                <div class="message-wrapper">
                  <div class="heading" style="text-align:center;">
                    <?php
                      echo "Vers&atilde;o " . $_SESSION['versao']
                    ?>
                  </div>
                  <div class="description" style="text-align:center;">
                    Visualizar as novidades!
                  </div>
                </div>
                <div class="clearfix"></div>
              </div>
            </a>
            </div>
          </div>
          <!-- END TOP NAVIGATION MENU -->
          <!-- BEGIN CHAT TOGGLER -->
          <div class="pull-right">
            <!-- <div class="chat-toggler sm">
              <div class="profile-pic">
                <img src="../assets/img/profiles/Aa.jpg" alt="" data-src="../assets/img/profiles/Aa.jpg" data-src-retina="../assets/img/profiles/Aa.jpg" width="35" height="35" />
                <div class="availability-bubble online"></div>
              </div>
            </div> -->
            <ul class="nav quick-section ">
              <li class="quicklinks">
                <a data-toggle="dropdown" class="dropdown-toggle  pull-right " href="#" id="user-options">
                  <i class="material-icons">tune</i>
                </a>
                <ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="user-options">
                  <li class="">
                    <?php echo '<a href="../perfil.php?id='.$id.'" title="Acesse seu perfil"><i class="fa fa-male fa-fw"></i>&nbsp;&nbsp;Meu perfil</a>';?>
                  </li>                  
                  <li class="divider"></li>
                  <li>
                    <a href="../logout.php"><i class="material-icons">power_settings_new</i>&nbsp;&nbsp;Sair</a>
                  </li>
                </ul>
              </li>
              <!--<li class="quicklinks"> <span class="h-seperate"></span></li>-->
              <!--<li class="quicklinks">-->
                <!-- <a href="#" class="chat-menu-toggle" data-webarch="toggle-right-side"><i class="material-icons">chat</i><span class="badge badge-important hide">1</span> -->
                <!--<a href="#" class="chat-menu-toggle"><i class="material-icons" title="Recurso ainda n&atilde;o implementado.">chat</i><span class="badge badge-important hide">1</span>-->
                <!--</a>-->
                <!--<div class="simple-chat-popup chat-menu-toggle hide">-->
                  <!--<div class="simple-chat-popup-arrow"></div>
                  <div class="simple-chat-popup-inner">
                     <div style="width:100px">
                      <div class="semi-bold">David Nester</div>
                      <div class="message">Hey you there </div>
                    </div> -->
                  <!--</div>
                </div>
              </li>-->
            </ul>
          </div>
          <!-- END CHAT TOGGLER -->
        </div>
        <!-- END TOP NAVIGATION MENU -->
      </div>
      <!-- END TOP NAVIGATION BAR -->
    </div>
    <!-- END HEADER -->
    <!-- CONTENT -->
    <div class="page-container row-fluid">
      <!-- SIDEBAR -->
      <div class="page-sidebar" id="main-menu">
        <!-- MINI PERFIL -->
        <div class="page-sidebar-wrapper scrollbar-dynamic" id="main-menu-wrapper">
          <div class="user-info-wrapper sm">
            <div class="profile-wrapper sm">
              <?php
                echo '<img width="69" height="69" src="data:image/jpeg;base64,'.base64_encode(stream_get_contents($result1['IMAGEM'])).'">';
              ?>
              <div class="availability-bubble online"></div>
            </div>
            <div class="user-info sm">
              <div class="username"><span class="semi-bold"> <?php echo $_SESSION['usuarioNome']; ?> </span></div>
              <div class="status">Seja bem-vindo(a)</div>
            </div>
          </div>
          <!-- /MINI PERFIL -->

          <!-- SIDEBAR MENU -->
          <p class="menu-title sm">MENU <span class="pull-right"><a href="javascript:;"><i class="material-icons">refresh</i></a></span></p>
          <ul>
            <li class=""> 
              <a href="../index.php"><i class="material-icons" title="Home">home</i> <span class="title">Home</span> <span class="title"></span> </a>
            </li>
            <li class=""> 
              <a href="../chamados.php"><i class="material-icons" title="Chamados">desktop_mac</i> <span class="title">Chamados</span></a>
            </li>
            <li class=""> 
              <a href="../ramais.php"><i class="material-icons" title="Ramais">phone_forwarded</i> <span class="title">Ramais</span></a>
            </li>
            <li class=""> 
              <a href="../cadastros.php"><i class="material-icons" title="Cadastros">library_add</i> <span class="title">Cadastros</span></a>
            </li>
            <li class=""> 
              <a href="../solicitacoes.php"><i class="material-icons" title="Solicita&ccedil;&otilde;es">assignment</i> <span class="title">Solicita&ccedil;&otilde;es</span></a>
            </li>
            <li class=""> 
              <a href="../uteis.php"><i class="fa fa-external-link" title="&uacute;teis"></i> <span class="title">Links &uacute;teis</span></a>
            </li>
            <?php
              if ($result1['GESTOR'] == 'S' || $result1['TIPO_USUARIO'] == 'ADM') {
                echo 
                '<li class="">
                  <a href="../indicadores.php"><i class="fa fa-bar-chart" title="Indicadores"></i> <span class="title">Indicadores</span></a>               
                </li>';
              }                
            ?>
           </ul>            
          <div class="clearfix"></div>
          <!-- /SIDEBAR MENU -->
          
        </div>
      </div>
      <a href="#" class="scrollup">Scroll</a>
      <div class="footer-widget">
        <div class="pull-left">
          <i class="material-icons">alarm</i>
          <iframe src="http://free.timeanddate.com/clock/i5hp9yxv/n595/tlbr5/fn17/fc555/tc22262e/pa0/th1" frameborder="0" width="66" height="14"></iframe>
        </div>
        <div class="pull-right">
          <a href="../bloquear.php"><i class="material-icons">lock_outline</i></a>
        </div>
      </div>
      <!-- /SIDEBAR -->

      <!-- CONTAINER-->
      <div class="page-content">
        <div class="content">
        <ul class="breadcrumb">
          <li>
            <p>VOC&Ecirc; EST&Aacute; EM </p>
          </li>
          <li>
            <a href="../index.php">Home</a>
          </li>
          <li>
            <a href="../dados.php">Dados</a> 
          </li>
          <li>
            <a href="#" class="active">Locais</a> 
          </li>
        </ul>

        <!-- TITULO -->
        <!--<div class="page-title"> <i class="fa fa-globe fa-5x"></i>
          <h3>Locais</h3>
        </div>-->
        <br>
        <br>
          <!-- /TITULO -->

          <!-- CONTEUDO -->
          
          <div class="row">
            <div class="col-md-12">
              <div class="grid simple ">
                <div class="grid-title no-border">
                  <div class="tools">
                    <span data-toggle="modal" data-target="#INModal"><a href="#" title="Adicionar"><i class="fa fa-plus fa-lg"></i></a></span>                   
                  </div>
                </div>
                <div class="grid-body no-border">
                  <h3><i class="fa fa-globe fa-1x"></i><span class="semi-bold">&nbsp; Locais</span></h3>
                  <table class="table table-hover" >
                    <thead>
                      <tr>                        
                        <th style="width:20%">Local</th>
                        <th style="width:80%">Nome</th>                    
                        <th style="width:10%">A&ccedil;&otilde;es</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php                      
                      foreach ($result2 as $key => $value) {
                        echo '
                          <tr>
                            <td class="v-align-middle">'.$result2[$key]['LOCAL'].'</td>
                            <td class="v-align-middle"><span class="muted">'.$result2[$key]['NOME'].'</span></td>
                            <td class="v-align-middle">
                              <span data-toggle="modal" data-target="#'.$result2[$key]['LOCAL'].'UPModal"><a href="#" title="Editar"><i class="fa fa-pencil"></i></a></span>
                              <span data-toggle="modal" data-target="#'.$result2[$key]['LOCAL'].'DLModal"><a href="#" title="Excluir"><i class="fa fa-trash"></i></a></span>
                              <span data-toggle="modal" data-target="#'.$result2[$key]['LOCAL'].'VWModal"><a href="#" title="Detalhes"><i class="fa fa-search"></i></a></span>
                            </td>
                          </tr>

                          <!-- MODAL UPDATE -->
                          <div class="modal fade" id="'.$result2[$key]['LOCAL'].'UPModal" tabindex="-1" role="dialog" aria-labelledby="'.$result2[$key]['LOCAL'].'UPModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">                                  
                                  <div>
                                    <div class="col-md-6 col-sm-6 col-xs-6" style="text-align:left;"></div>
                                    <div class="col-md-6 col-sm-6 col-xs-6" style="text-align:right;"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button></div>
                                  </div>
                                  <br>
                                  <i class="fa fa-pencil-square-o fa-6x"></i>
                                  <h4 id="1ModalLabel" class="semi-bold">Local: '.$result2[$key]['NOME'].'</h4>
                                </div>
                                <div class="modal-body">
                                  <div class="">
                                    <div class="row" style="line-height:2;">
                                      <form method="post" name="local" action="locais.U.php">                                      

                                        <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                          <div class="controls">
                                            <input type="text" placeholder="Local" value="'.$result2[$key]['LOCAL'].'" class="form-control input" name="local" maxlength="5" readonly required>
                                          </div>
                                        </div>

                                        <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                          <div class="controls">
                                            <input type="text" placeholder="Nome" value="'.$result2[$key]['NOME'].'" class="form-control input" name="nome" maxlength="40" required>
                                          </div>
                                        </div>                                        
                                        
                                        <div class="form-group col-md-12 col-sm-12 col-xs-12 pull-right">
                                          <button type="submit" class="btn btn-info btn-block" value="submit"> Atualizar</button>                                        
                                        </div>                                                                                                                                           
                                      
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <!-- MODAL DELETE -->
                          <div class="modal fade" id="'.$result2[$key]['LOCAL'].'DLModal" tabindex="-1" role="dialog" aria-labelledby="'.$result2[$key]['LOCAL'].'DLModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                  <br>
                                  <i class="fa fa-trash fa-6x"></i>
                                  <h4 id="1ModalLabel" class="semi-bold">Excluir</h4>
                                </div>
                                <div class="modal-body">
                                  <div class="alert alert-danger">
                                    <i class="pull-left material-icons">feedback</i>
                                    <div>
                                      <span style="padding-left: 20px;">
                                        Voc&ecirc; tem certeza que deseja excluir <strong> '.$result2[$key]['LOCAL'].' </strong> ?                                             
                                      </span>
                                      <div class="pull-right">
                                      <a href="locais.D.php?id='.$result2[$key]['LOCAL'].'"><button class="btn btn-danger btn-small">Sim </button></a>
                                      <button type="button" class="btn btn-default btn-small" data-dismiss="modal">N&atilde;o </button>    
                                      </div>
                                      </div>
                                  </div>             
                                </div>
                              </div>
                            </div>
                          </div>

                          <!-- MODAL VIEW -->
                          <div class="modal fade" id="'.$result2[$key]['LOCAL'].'VWModal" tabindex="-1" role="dialog" aria-labelledby="'.$result2[$key]['LOCAL'].'VWModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                  <br>
                                  <i class="fa fa-globe fa-6x"></i>
                                  <h4 id="1ModalLabel" class="semi-bold">Local: '.$result2[$key]['NOME'].'</h4>
                                </div>
                                <div class="modal-body">
                                  <div class="">
                                    <div class="row" style="line-height:2;">

                                      <div class="col-md-12">                                                                  
                                        <strong>LOCAL: </strong>'.$result2[$key]['LOCAL'].'                                
                                      </div>

                                      <div class="col-md-12">                                                                  
                                        <strong>NOME: </strong>'.$result2[$key]['NOME'].'                                
                                      </div>                                      
                                      
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          ';                      
                      }                        
                      ?>
                    </tbody>
                  </table>
                </div>

                 <!-- MODAL INSERT -->
                <div class="modal fade" id="INModal" tabindex="-1" role="dialog" aria-labelledby="INModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">                                  
                        <div>
                          <div class="col-md-6 col-sm-6 col-xs-6" style="text-align:left;"></div>
                          <div class="col-md-6 col-sm-6 col-xs-6" style="text-align:right;"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button></div>
                        </div>
                        <br>
                        <i class="fa fa-globe fa-6x"></i>
                        <h4 id="1ModalLabel" class="semi-bold">Novo Local</h4>
                      </div>
                      <div class="modal-body">
                        <div class="">
                          <div class="row" style="line-height:2;">
                            <form method="post" name="local" action="locais.I.php">                                                                                             

                              <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                <div class="controls">
                                  <input type="text" placeholder="Local" value="" class="form-control input" name="local" maxlength="5" required>
                                </div>
                              </div>

                              <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                <div class="controls">
                                  <input type="text" placeholder="Nome" value="" class="form-control input" name="nome" maxlength="40" required>
                                </div>
                              </div>
                              
                              <div class="form-group col-md-12 col-sm-12 col-xs-12 pull-right">
                                <button type="submit" class="btn btn-info btn-block" value="submit"> Cadastrar</button>                                        
                              </div>                                                                                                                                           
                            
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>       
          <!-- /CONTEUDO -->
        </div>
      </div>
      <!-- CONTAINER -->

      <!-- BEGIN CHAT -->
      <div class="chat-window-wrapper">
        <div id="main-chat-wrapper" class="inner-content">
          <div class="chat-window-wrapper scroller scrollbar-dynamic" id="chat-users">
            <!-- BEGIN CHAT HEADER -->
            <div class="chat-header">
              <!-- BEGIN CHAT SEARCH BAR -->
              <div class="pull-left">
                <input type="text" placeholder="search">
              </div>
              <!-- END CHAT SEARCH BAR -->
              <!-- BEGIN CHAT QUICKLINKS -->
              <div class="pull-right">
                <a href="#" class="">
                  <div class="iconset top-settings-dark"></div>
                </a>
              </div>
              <!-- END CHAT QUICKLINKS -->
            </div>
            <!-- END CHAT HEADER -->
            <!-- BEGIN GROUP WIDGET -->
            <div class="side-widget">
              <div class="side-widget-title">group chats</div>
              <div class="side-widget-content">
                <div id="groups-list">
                  <ul class="groups">
                    <li>
                      <a href="#">
                        <div class="status-icon green"></div>Group Chat 1</a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <!-- END GROUP WIDGET -->
            <!-- BEGIN FAVORITES WIDGET -->
            <div class="side-widget">
              <div class="side-widget-title">favorites</div>
              <div class="side-widget-content">
                <!-- BEGIN SAMPLE CHAT -->
                <div class="user-details-wrapper active" data-chat-status="online" data-chat-user-pic="../assets/img/profiles/d.jpg" data-chat-user-pic-retina="../assets/img/profiles/d2x.jpg" data-user-name="Jane Smith">
                  <!-- BEGIN PROFILE PIC -->
                  <div class="user-profile">
                    <img src="../assets/img/profiles/d.jpg" alt="" data-src="../assets/img/profiles/d.jpg" data-src-retina="../assets/img/profiles/d2x.jpg" width="35" height="35">
                  </div>
                  <!-- END PROFILE PIC -->
                  <!-- BEGIN MESSAGE -->
                  <div class="user-details">
                    <div class="user-name">Jane Smith</div>
                    <div class="user-more">Message...</div>
                  </div>
                  <!-- END MESSAGE -->
                  <!-- BEGIN MESSAGES BADGE -->
                  <div class="user-details-status-wrapper">
                    <span class="badge badge-important">3</span>
                  </div>
                  <!-- END MESSAGES BADGE -->
                  <!-- BEGIN STATUS -->
                  <div class="user-details-count-wrapper">
                    <div class="status-icon green"></div>
                  </div>
                  <!-- END STATUS -->
                  <div class="clearfix"></div>
                </div>
                <!-- END SAMPLE CHAT -->
              </div>
            </div>
            <!-- END FAVORITES WIDGET -->
            <!-- BEGIN MORE FRIENDS WIDGET -->
            <div class="side-widget">
              <div class="side-widget-title">more friends</div>
              <div class="side-widget-content" id="friends-list">
                <!-- BEGIN SAMPLE CHAT -->
                <div class="user-details-wrapper" data-chat-status="online" data-chat-user-pic="../assets/img/profiles/d.jpg" data-chat-user-pic-retina="../assets/img/profiles/d2x.jpg" data-user-name="Jane Smith">
                  <!-- BEGIN PROFILE PIC -->
                  <div class="user-profile">
                    <img src="../assets/img/profiles/d.jpg" alt="" data-src="../assets/img/profiles/d.jpg" data-src-retina="../assets/img/profiles/d2x.jpg" width="35" height="35">
                  </div>
                  <!-- END PROFILE PIC -->
                  <!-- BEGIN MESSAGE -->
                  <div class="user-details">
                    <div class="user-name">Jane Smith</div>
                    <div class="user-more">Message...</div>
                  </div>
                  <!-- END MESSAGE -->
                  <!-- BEGIN MESSAGES BADGE -->
                  <div class="user-details-status-wrapper">
                    <span class="badge badge-important">3</span>
                  </div>
                  <!-- END MESSAGES BADGE -->
                  <!-- BEGIN STATUS -->
                  <div class="user-details-count-wrapper">
                    <div class="status-icon green"></div>
                  </div>
                  <!-- END STATUS -->
                  <div class="clearfix"></div>
                </div>
                <!-- END SAMPLE CHAT -->
              </div>
            </div>
            <!-- END MORE FRIENDS WIDGET -->
          </div>
          <!-- BEGIN DUMMY CHAT CONVERSATION -->
          <div class="chat-window-wrapper" id="messages-wrapper" style="display:none">
            <!-- BEGIN CHAT HEADER BAR -->
            <div class="chat-header">
              <!-- BEGIN SEARCH BAR -->
              <div class="pull-left">
                <input type="text" placeholder="search">
              </div>
              <!-- END SEARCH BAR -->
              <!-- BEGIN CLOSE TOGGLE -->
              <div class="pull-right">
                <a href="#" class="">
                  <div class="iconset top-settings-dark"></div>
                </a>
              </div>
              <!-- END CLOSE TOGGLE -->
            </div>
            <div class="clearfix"></div>
            <!-- END CHAT HEADER BAR -->
            <!-- BEGIN CHAT BODY -->
            <div class="chat-messages-header">
              <div class="status online"></div>
              <span class="semi-bold">Jane Smith(Typing..)</span>
              <a href="#" class="chat-back"><i class="icon-custom-cross"></i></a>
            </div>
            <!-- BEGIN CHAT MESSAGES CONTAINER -->
            <div class="chat-messages scrollbar-dynamic clearfix">
              <!-- BEGIN TIME STAMP EXAMPLE -->
              <div class="sent_time">Yesterday 11:25pm</div>
              <!-- END TIME STAMP EXAMPLE -->
              <!-- BEGIN EXAMPLE CHAT MESSAGE -->
              <div class="user-details-wrapper">
                <!-- BEGIN MESSENGER PROFILE -->
                <div class="user-profile">
                  <img src="../assets/img/profiles/d.jpg" alt="" data-src="../assets/img/profiles/d.jpg" data-src-retina="../assets/img/profiles/d2x.jpg" width="35" height="35">
                </div>
                <!-- END MESSENGER PROFILE -->
                <!-- BEGIN MESSENGER MESSAGE -->
                <div class="user-details">
                  <div class="bubble">Hello, You there?</div>
                </div>
                <!-- END MESSENGER MESSAGE -->
                <div class="clearfix"></div>
                <!-- BEGIN TIMESTAMP ON CLICK TOGGLE -->
                <div class="sent_time off">Yesterday 11:25pm</div>
                <!-- END TIMESTAMP ON CLICK TOGGLE -->
              </div>
              <!-- END EXAMPLE CHAT MESSAGE -->
              <!-- BEGIN TIME STAMP EXAMPLE -->
              <div class="sent_time">Today 11:25pm</div>
              <!-- BEGIN TIME STAMP EXAMPLE -->
              <!-- BEGIN EXAMPLE CHAT MESSAGE (FROM SELF) -->
              <div class="user-details-wrapper pull-right">
                <!-- BEGIN MESSENGER MESSAGE -->
                <div class="user-details">
                  <div class="bubble sender">Let me know when you free</div>
                </div>
                <!-- END MESSENGER MESSAGE -->
                <div class="clearfix"></div>
                <!-- BEGIN TIMESTAMP ON CLICK TOGGLE -->
                <div class="sent_time off">Sent On Tue, 2:45pm</div>
                <!-- END TIMESTAMP ON CLICK TOGGLE -->
              </div>
              <!-- END EXAMPLE CHAT MESSAGE (FROM SELF) -->
            </div>
            <!-- END CHAT MESSAGES CONTAINER -->
          </div>
          <div class="chat-input-wrapper" style="display:none">
            <textarea id="chat-message-input" rows="1" placeholder="Type your message"></textarea>
          </div>
          <div class="clearfix"></div>
          <!-- END DUMMY CHAT CONVERSATION -->
        </div>
      </div>
      <!-- END CHAT -->
    </div>
    <!-- END CONTENT -->
    <!-- BEGIN CORE JS FRAMEWORK-->
    <script src="../assets/plugins/pace/pace.min.js" type="text/javascript"></script>
    <!-- BEGIN JS DEPENDECENCIES-->
    <script src="../assets/plugins/jquery/jquery-1.11.3.min.js" type="text/javascript"></script>
    <script src="../assets/plugins/bootstrapv3/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../assets/plugins/jquery-block-ui/jqueryblockui.min.js" type="text/javascript"></script>
    <script src="../assets/plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script>
    <script src="../assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js" type="text/javascript"></script>
    <script src="../assets/plugins/jquery-numberAnimate/jquery.animateNumbers.js" type="text/javascript"></script>
    <script src="../assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="../assets/plugins/bootstrap-select2/select2.min.js" type="text/javascript"></script>
    <script src="../assets/plugins/jquery-datatable/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="../assets/plugins/jquery-datatable/extra/js/dataTables.tableTools.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="../assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
    <script type="text/javascript" src="../assets/plugins/datatables-responsive/js/lodash.min.js"></script>
    <!-- END CORE JS DEPENDECENCIES-->
    <!--<script type="text/javascript">
      $(document).ready(function() {
        $('#tLocais').DataTable( {
          "paging":   false
          "oLanguage": {
            "sLengthMenu": "_MENU_",
            "sZeroRecords": "Nenhum registro encontrado",
            "sInfo": " Mostrando _START_ / _END_ de _TOTAL_ registro(s)",
            "sInfoEmpty": "Mostrando 0 / 0 de 0 registros",
            "sInfoFiltered": "(filtrado de _MAX_ registros)",
            "sSearch": "Pesquisar: ",
            "sEmptyTable": "Nenhum registro encontrado",
            "oPaginate": {
                "sFirst": "In&iacute;cio",
                "sPrevious": "Anterior ",
                "sNext": "Próximo ",
                "sLast": "Último"
            }
        }
        
    } );
} );
    </script>-->
    <!-- BEGIN CORE TEMPLATE JS -->
    <script src="../webarch/js/webarch.js" type="text/javascript"></script>
    <script src="../assets/js/chat.js" type="text/javascript"></script>
    <script src="../assets/js/datatables.js" type="text/javascript"></script>
    <!-- END CORE TEMPLATE JS -->
  </body>
</html>