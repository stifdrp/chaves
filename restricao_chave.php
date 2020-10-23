<?php 
    include './header.php';
?>

        <div class="container">
            <h2 style="text-align: center; margin-bottom: 1.5rem; background-color: #c5c5c5;">Restrição de Salas</h2>
            <h3> <?php echo $_SESSION['texto_chaves_restritas']; ?></h3>
            <button onclick="window.location.href = 'selecionar_chaves.php';">OK</button>
            <a class=""href="">
        </div>
    </body>
</html>