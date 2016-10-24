<?php if (count($languages) > 1) { ?>
  <div class="topbar__language">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="language">
      <div class="btn-group">
        <button class="btn btn-link dropdown dropdown-toggle" data-toggle="dropdown">
          <span><?php echo $text_language; ?>:</span>
          <?php echo $code;?>
          <span class="pe-7s-angle-down"></span>
        </button>
        <ul class="dropdown-menu">
          <?php foreach ($languages as $language) { ?>
            <li>
              <a href="<?php echo $language['code']; ?>">
                <img src="image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>"/> <?php echo $language['name']; ?>
              </a>
            </li>
          <?php } ?>
        </ul>
      </div>
      <input type="hidden" name="code" value=""/>
      <input type="hidden" name="redirect" value="<?php echo $redirect; ?>"/>
    </form>
  </div>
<?php } ?>
