<?php if(!defined('APPLICATION')) exit();
/* Copyright 2014 Zachary Doll */

echo Wrap($this->Data('Title'), 'h1');

echo $this->Form->Open();
echo $this->Form->Errors();
?>
<ul>
  <li>
    <?php
    echo $this->Form->Label('Name', 'Name');
    echo $this->Form->TextBox('Name');
    ?>
  </li>
  <li>
    <?php
    echo $this->Form->Label('Insert', 'Body');
    echo $this->Form->TextBox('Body', array('multiline' => TRUE));
    ?>
  </li>
  <li id="MobileBodyCheck">
    <?php
    echo $this->Form->CheckBox('MobileBodyCheck', 'Use a separate insert for Mobile users?', array('checked' => 'checked'));
    ?>
  </li>
  <li id="MobileBodyRow">
    <?php
    echo $this->Form->Label('Mobile Insert', 'MobileBody');
    echo $this->Form->TextBox('MobileBody', array('multiline' => TRUE));
    ?>
  </li>
</ul>
<?php
echo $this->Form->Close('Save');
