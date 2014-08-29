<?php if (!defined('APPLICATION')) exit();
/* Copyright 2014 Zachary Doll */

echo Wrap($this->Data('Title'), 'h1');

echo $this->Form->Open();
echo $this->Form->Errors();

echo Wrap(Wrap(T('Discussion Inserts allows you to add custom text or HTML between the starting post and the first answer of a discussion.'), 'div'), 'div', array('class' => 'Wrap'));
echo Wrap(Anchor(T('Add Insert'), 'settings/discussioninserts/add', array('class' => 'SmallButton')), 'div', array('class' => 'Wrap'));

?>
<table id="Actions" class="AltRows">
  <thead>
    <tr>
      <th><?php echo T('Name'); ?></th>
      <th><?php echo T('Discussion'); ?></th>
      <th><?php echo T('Insert'); ?></th>
      <th><?php echo T('Mobile Insert'); ?></th>
      <th><?php echo T('Options'); ?></th>
    </tr>
  </thead>
  <tbody>
    <?php
    $Alt = 'Alt';
    foreach($this->Data('DiscussionInserts') as $DiscussionInsert) {
      $Alt = $Alt ? '' : 'Alt';
      $Row = '';
      $Row .= Wrap(Gdn_Format::Html($DiscussionInsert->Name), 'td');
      $Row .= Wrap(Anchor(T('[Link]'), '/discussion/' . $DiscussionInsert->DiscussionID), 'td');
      $Row .= Wrap(Gdn_Format::Html($DiscussionInsert->Body), 'td');
      $Row .= Wrap(Gdn_Format::Html($DiscussionInsert->MobileBody), 'td');
      $Row .= Wrap(Anchor(T('Edit'), 'settings/discussioninserts/edit/' . $DiscussionInsert->DiscussionInsertID, array('class' => 'SmallButton')) . Anchor(T('Delete'), 'settings/discussioninserts/delete/' . $DiscussionInsert->DiscussionInsertID, array('class' => 'Danger Popup SmallButton')), 'td');
      echo Wrap($Row, 'tr', array('id' => 'DiscussionInsertID_' . $DiscussionInsert->DiscussionInsertID, 'data-discussioninsertid' => $DiscussionInsert->DiscussionInsertID, 'class' => $Alt));
    }
    ?>
  </tbody>
</table>