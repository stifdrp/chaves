<nav class="navbar">
    <div class="container-fluid" style="background-color: #8693c9;">
        <div class="container">
                <ul class="nav nav-pills pull-left">
                    <li class="nav-item">
                        <a class="nav-link" href="./selecionar_chaves.php">Reservas</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Pessoas</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="./cadpessoa.php">Cadastro</a>
                            <a class="dropdown-item" href="./listausuario.php">Listagem</a>
                        </div>
                    </li>
                    <?php 
                        $perfis_habilitados = array(1);
                        if(in_array($_SESSION['perfil'], $perfis_habilitados)){
                    ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Chaves</a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="./cadchave.php">Cadastro</a>
                                    <a class="dropdown-item" href="./listachave.php">Listagem</a>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./relatorio.php">Relatorio</a>
                            </li>
                    <?php }?>    
                </ul>
                <ul class="nav nav-pills pull-right">
                    <?php
                        if(isset($_SESSION['nome']) && !empty($_SESSION['nome'])){
                            echo '<li class="nav-item dropdown">';
                                echo '<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">' . $_SESSION['nome'] . '</a>';
                                echo '<div class="dropdown-menu">';
                                    echo '<a class="dropdown-item" href="./logout.php">Logout</a>';
                                echo '</div>';
                            echo '</li>';
                        } else {
                            echo '<li class="nav-item"><a class="nav-link" href="./login.php">Login</a></li>';
                        }
                    ?>
                </ul>
        </div>    
        </div>
        </nav>