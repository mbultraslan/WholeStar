<?php if (count($currencies) > 1) { ?>
  <div class="topbar__currency">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="currency">
      <div class="btn-group">
        <button class="btn btn-link dropdown dropdown-toggle" data-toggle="dropdown">
          <span><?php echo $text_currency; ?>:</span>
          <?php echo $code;?>
          <span class="pe-7s-angle-down"></span>
        </button>
        <ul class="dropdown-menu">
          <?php foreach ($currencies as $currency) { ?>
            <?php if ($currency['symbol_left']) { ?>
              <li>
                <button class="currency-select btn btn-link btn-block" type="button" name="<?php echo $currency['code']; ?>"><?php echo $currency['symbol_left']; ?> <?php echo $currency['title']; ?></button>
              </li>
            <?php } else { ?>
              <li>
                <button class="currency-select btn btn-link btn-block" type="button" name="<?php echo $currency['code']; ?>"><?php echo $currency['symbol_right']; ?> <?php echo $currency['title']; ?></button>
              </li>
            <?php } ?>
          <?php } ?>
        </ul>
      </div>
      <input type="hidden" name="code" value=""/>
      <input type="hidden" name="redirect" value="<?php echo $redirect; ?>"/>
    </form>
  </div>
<?php } ?>
