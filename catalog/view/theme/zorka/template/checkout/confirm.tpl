<?php if (!isset($redirect)) { ?>
<table class="table table-bordered table-hover">
  <thead>
    <tr>
      <td class="text-left"><?php echo $column_name; ?></td>
      <td class="text-left"><?php echo $column_model; ?></td>
      <td class="text-right"><?php echo $column_quantity; ?></td>
      <td class="text-left"><?php echo $column_price; ?></td>
      <td class="text-right"><?php echo $column_total; ?></td>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($products as $product) { ?>
    <tr>
      <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
        <?php foreach ($product['option'] as $option) { ?>
          <br/>
          &nbsp;
          <?php if($option['type']!='text') {
          ?>
          <small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
        <?php
         }
         } ?>

        <?php if ($product['recurring']) { ?>
          <br/>
          <span class="label label-info"><?php echo $text_recurring; ?></span>
          <small><?php echo $product['recurring']; ?></small>
        <?php } ?>
      </td>
      <td class="text-left"><?php echo $product['model']; ?></td>
      <td class="text-right">
        <?php
        $totalPack = 0;
        foreach ($product['packData'] as $pack) {
          $totalPack += $pack['qty'];
        }

        if ($product['packQty'] !== 0) {
          ?>

            <?php echo $totalPack; ?> Pack (<?php echo $product['packQty']; ?> pieces)
          <br/>
          <?php
        }
        ?>
        <?php
        if ($product['singleQty'] !== 0) {
        ?>

          <?php echo $product['singleQty']; ?> Pieces in single

        <?php
        }
        ?>
        <hr style="margin:0"/>
        <span style="font-weight: bold">Total :</span>
        <?php echo $product['packQty'] + $product['singleQty']; ?> Pieces
      </td>
      <td class="text-left">
        <?php
        if ($product['packQty'] !== 0) {
          ?>
          <?php echo $product['packPrice']; ?>/each for pack<br/>
          <?php
        }

        if ($product['sinleQty'] !== 0) {
          ?>
          <?php echo $product['eachPrice']; ?>/each for single
          <?php
        }
        ?>

      </td>
      <td class="text-right"><?php echo $product['total']; ?></td>
    </tr>
    <?php } ?>
    <?php foreach ($vouchers as $voucher) { ?>
    <tr>
      <td class="text-left"><?php echo $voucher['description']; ?></td>
      <td class="text-left"></td>
      <td class="text-right">1</td>
      <td class="text-right"><?php echo $voucher['amount']; ?></td>
      <td class="text-right"><?php echo $voucher['amount']; ?></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php foreach ($totals as $total) { ?>
    <tr>
      <td colspan="4" class="text-right"><strong><?php echo $total['title']; ?>:</strong></td>
      <td class="text-right"><?php echo $total['text']; ?></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>
<?php
$inline_payments = array('bank_transfer', 'cheque');
if (!Kuler::getInstance()->getSkinOption('enable_one_page_checkout') || (!empty(Kuler::getInstance()->session->data['payment_method']) && !empty(Kuler::getInstance()->session->data['payment_method']['code']) && in_array(Kuler::getInstance()->session->data['payment_method']['code'], $inline_payments))) {  ?>
  <div class="payment"><?php echo $payment; ?></div>
<?php } ?>
<?php } else { ?>
<script type="text/javascript"><!--
location = '<?php echo $redirect; ?>';
//--></script>
<?php } ?>
