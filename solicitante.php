<?php 
    include './header.php';
    include './Emprestimo.php';
    if(isset($_POST['chaves']) && !empty($_POST['chaves'])){
        $_SESSION['chaves'] = $_POST['chaves'];
    }
    
    if(isset($_POST['login']) && !empty($_POST['login'])){
        $login = addslashes(intval($_POST['login']));
        $senha = addslashes($_POST['senha']);
        $obs = addslashes($_POST['observacao']);
        if (Emprestimo::validaLogin($login, $senha)){
            Emprestimo::emprestar($_SESSION['id'], $login, $_SESSION['chaves'], $obs);
            $_SESSION['chaves'] = NULL;
            if (isset($_SESSION['texto_chaves_restritas']) && !empty($_SESSION['texto_chaves_restritas'])){
                header("Location: restricao_chave.php");
            } else {
                header("Location: selecionar_chaves.php");
            }
        } else {
            echo '<script type="text/javascript">alert("Usuário e senha não conferem");</script>';
        }
    }
?>
        <div class="container">
            <div class="form-signin">
            <h3>A(s) sala(s) que está(ão) sendo reservada(s) são: </h3>
                <h4>
                    <?php
                    $string = array();
                    foreach ($_SESSION['chaves'] as $chave) {
                        $string[] = Emprestimo::pegaDescricao($chave); 
                    }
                    $string = implode(', ', $string);
                    echo $string;
                    ?>
                </h4>
            </div>
            
            <form class="form-signin" method="POST">
                <label class="sr-only" for="login">Identificação</label>
                <input class="form-control" id="login" type="text" name="login" required="" placeholder="Identificação" autofocus=""/>
                <label class="sr-only" for="senha">Senha</label>
                <input class="form-control" id="senha" type="password" name="senha" placeholder="Senha" required="" />
                <label class="sr-only" for="observacao">Observação</label>
                <textarea class="form-control" id="observacao" type="text" name="observacao" placeholder="observacao" rows="3"><?php echo (isset($obs) && !empty($obs))?$obs:"";?></textarea>
                <br/>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Autorizar</button>
            </form>
        </div>
    </body>
</html>

