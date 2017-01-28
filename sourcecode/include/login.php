<div class="row-fluid">
    <div class="span12">
        <?php if ($error != "") { ?>
            <p class="alert-error"><?php echo $error; ?></p>
        <?php } ?>
        <form role="form" action="load.php" method="post">
          <div class="form-group">
            <label for="user">Usuario</label>
            <input type="text" class="form-control" id="user" name="user" placeholder="Nombre de usuario" required autofocus>
          </div>
          <div class="form-group">
            <label for="pass">Contraseña</label>
            <input type="password" class="form-control" id="pass" name="pass" placeholder="Contraseña" required>
          </div>         
          <button type="submit" class="btn btn-inverse"><i class="icon-share icon-white"></i> Log in</button>
        </form>
    </div>
</div>