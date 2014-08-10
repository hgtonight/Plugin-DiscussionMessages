<?php if (!defined('APPLICATION')) exit();
/*	Copyright 2014 Zachary Doll
*	This program is free software: you can redistribute it and/or modify
*	it under the terms of the GNU General Public License as published by
*	the Free Software Foundation, either version 3 of the License, or
*	(at your option) any later version.
*
*	This program is distributed in the hope that it will be useful,
*	but WITHOUT ANY WARRANTY; without even the implied warranty of
*	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*	GNU General Public License for more details.
*
*	You should have received a copy of the GNU General Public License
*	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
$PluginInfo['DiscussionMessages'] = array(
	'Name' => 'Discussion Messages',
	'Description' => 'Adds messages to specific discussions.',
	'Version' => '0.1',
	'RequiredApplications' => array('Vanilla' => '2.0.18.13'),
	'RequiredTheme' => FALSE,
	'RequiredPlugins' => FALSE,
	'MobileFriendly' => TRUE,
	'HasLocale' => TRUE,
	'RegisterPermissions' => FALSE,
  'SettingsUrl' => '/settings/discussionmessages',
	'SettingsPermission' => 'Garden.Settings.Manage',
	'Author' => 'Zachary Doll',
	'AuthorEmail' => 'hgtonight@daklutz.com',
	'AuthorUrl' => 'http://www.daklutz.com',
	'License' => 'GPLv3'
);

class DiscussionMessages extends Gdn_Plugin {

	// add a Testing Ground page on the settings controller
	public function SettingsController_DiscussionMessages_Create($Sender) {
		$Sender->AddSideMenu('settings/discussionmessages');
		$this->Dispatch($Sender, $Sender->RequestArgs);
	}
  
  public function Controller_Index($Sender) {
    $Sender->Title($this->GetPluginName() . ' ' . T('Settings'));
    
    $DiscussionMessageModel = new DiscussionMessageModel();
    $DiscussionMessages = $DiscussionMessageModel->Get();
    
    $Sender->SetData('DiscussionMessages', $DiscussionMessages);
		$Sender->Render($this->GetView('settings.php'));
  }
  
  public function Controller_Add($Sender) {
    $this->Controller_Edit($Sender);
  }
	
  public function Controller_Edit($Sender) {
    $Sender->Permission('Garden.Settings.Manage');
    
    $DiscussionMessageModel = new DiscussionMessageModel();
    $Sender->Form->SetModel($DiscussionMessageModel);

    $Sender->Title(T('Add Discussion Message'));
    $Edit = FALSE;
    $MessageID = GetValue(1, $Sender->RequestArgs, FALSE);
    if($MessageID) {
      $Sender->DiscussionMessage = $DiscussionMessageModel->GetID($MessageID);
      $Sender->Form->AddHidden('DiscussionMessageID', $MessageID);
      $Edit = TRUE;
      $Sender->Title(T('Edit Discussion Message'));
    }

    if($Sender->Form->IsPostBack() == FALSE) {
      if(property_exists($Sender, 'DiscussionMessage')) {
        $Sender->Form->SetData($Sender->DiscussionMessage);
      }
    }
    else {
      if($Sender->Form->Save()) {
        if($Edit) {
          $Sender->InformMessage(T('Discussion Message updated successfully!'));
        }
        else {
          $Sender->InformMessage(T('Discussion Message added successfully!'));
        }
        Redirect('/settings/discussionmessages');
      }
    }

    $Sender->Render($this->GetView('edit.php'));
  }
  
  public function Controller_Delete($Sender) {
    $DiscussionMessageModel = new DiscussionMessageModel();
    
    $MessageID = GetValue(1, $Sender->RequestArgs, FALSE);
    $DiscussionMessage = $DiscussionMessageModel->GetID($MessageID);

    if(!$DiscussionMessage) {
      throw NotFoundException(T('Discussion Message'));
    }

    $Sender->Permission('Garden.Settings.Manage');

    $Sender->SetData('Title', T('Delete Discussion Message'));
    if($Sender->Form->IsPostBack()) {
      if($DiscussionMessageModel->Delete($MessageID)) {
        $Sender->Form->AddError(T('Unable to delete discussion message!'));
      }

      if($Sender->Form->ErrorCount() == 0) {
        if($Sender->_DeliveryType === DELIVERY_TYPE_ALL) {
          Redirect('settings/discussionmessages');
        }

        $Sender->JsonTarget('#DiscussionMessageID_' . $MessageID, NULL, 'SlideUp');
      }
    }
    $Sender->Render($this->GetView('delete.php'));
  }
  
  public function DiscussionController_AfterDiscussion_Handler($Sender) {
    $DiscussionMessageModel = new DiscussionMessageModel();
    $Discussion = GetValue('Discussion', $Sender->EventArguments);
    $DiscussionID = $Discussion->DiscussionID;
    $Message = $DiscussionMessageModel->GetID($DiscussionID);
    if($Message) {
      echo Gdn_Format::Html($Message->Body);
    }
    var_dump($Message);
  }
	
	public function Setup() {
    $this->Structure();
	}

	public function Structure() {
    $Database = Gdn::Database();
    $Construct = $Database->Structure();

    $Construct->Table('DiscussionMessage');
    $Construct
            ->PrimaryKey('DiscussionMessageID')
            ->Column('Body', 'text', FALSE, 'fulltext')
            ->Column('DiscussionID', 'int', FALSE)
            ->Set();
  }

	public function OnDisable() {
		return TRUE;
	}
}