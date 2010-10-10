<div class="sf_admin_list">
  <?php if (!$pager->getNbResults()): ?>
    <h2><?php echo __('No result') ?></h2>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th><input class="sf_admin_list_batch_checkbox" type="checkbox" /></th>
          <th></th>
          <?php include_partial('lrNewsletterAdmin/list_th_tabular', array('sort' => $sort)) ?>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th><input class="sf_admin_list_batch_checkbox" type="checkbox" /></th>
          <th></th>
          <?php include_partial('lrNewsletterAdmin/list_th_tabular', array('sort' => $sort)) ?>
        </tr>
      </tfoot>
      <tbody class='{toggle_url: "<?php echo £link('@'.$helper->getUrlForAction('toggleBoolean'))->getHref() ?>"}'>
        <?php foreach ($pager->getResults() as $i => $lr_newsletter): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?> {pk: <?php echo $lr_newsletter->getPrimaryKey() ?>}">
            <td>
              <input type="checkbox" name="ids[]" value="<?php echo $lr_newsletter->getPrimaryKey() ?>" class="sf_admin_batch_checkbox" />
            </td>
            <?php include_partial('lrNewsletterAdmin/list_td_actions', array('lr_newsletter' => $lr_newsletter)) ?>
            <?php include_partial('lrNewsletterAdmin/list_td_tabular', array('lr_newsletter' => $lr_newsletter)) ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>