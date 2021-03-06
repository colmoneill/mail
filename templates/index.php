<?php
script('mail', 'jquery.storageapi');
script('mail', 'jquery-visibility');
?>
<script id="mail-folder-template" type="text/x-handlebars-template">
	<li data-folder_id="{{id}}" data-no_select="{{noSelect}}"
		class="
		{{#if unseen}}unread{{/if}}
		{{#if active}} active{{/if}}
		{{#if specialRole}} special-{{specialRole}}{{/if}}
		{{#if folders}} collapsible{{/if}}
		{{#if open}} open{{/if}}
		">
		{{#if folders}}<button class="collapse"></button>{{/if}}
		<a class="folder {{#if specialRole}} icon-{{specialRole}} svg{{/if}}">
			{{name}}
			{{#if unseen}}
			<span class="utils">{{unseen}}</span>
			{{/if}}
		</a>
		<ul>
			{{#each folders}}
			<li data-folder_id="{{id}}"
				class="
		{{#if unseen}}unread{{/if}}
		{{#if specialRole}} special-{{specialRole}}{{/if}}
		">
				<a class="folder
					{{#if specialRole}} icon-{{specialRole}} svg{{/if}}">
					{{name}}
					{{#if unseen}}
					<span class="utils">{{unseen}}</span>
					{{/if}}
				</a>
				{{/each}}
		</ul>
	</li>
</script>
<script id="mail-account-template" type="text/x-handlebars-template">
	{{#if email}}
	<div class="mail-account-color" style="background-color: {{accountColor email}}"></div>
	{{/if}}
	<h2 class="mail-account-email" title="{{email}}">{{email}}</h2>
	<ul id="mail_folders" class="mail_folders with-icon" data-account_id="{{id}}">
	</ul>
</script>
<script id="mail-messages-template" type="text/x-handlebars-template">
	<div class="mail_message_summary {{#if flags.unseen}}unseen{{/if}} {{#if active}}active{{/if}}" data-message-id="{{id}}">
		{{#if accountMail}}
		<div class="mail-message-account-color" style="background-color: {{accountColor accountMail}}"></div>
		{{/if}}
		<div class="mail-message-header">
			<div class="sender-image">
				{{#if senderImage}}
				<img src="{{senderImage}}" width="32px" height="32px" />
				{{else}}
				<div class="avatar" data-user="{{from}}" data-size="32"></div>
				{{/if}}
			</div>

			{{#if flags.flagged}}
			<div class="star icon-starred" data-starred="true"></div>
			{{else}}
			<div class="star icon-star" data-starred="false"></div>
			{{/if}}

			{{#if flags.answered}}
			<div class="icon-reply"></div>
			{{/if}}

			{{#if flags.hasAttachments}}
			<div class="icon-public icon-attachment"></div>
			{{/if}}

			<div class="mail_message_summary_from" title="{{fromEmail}}">{{from}}</div>
			<div class="mail_message_summary_subject" title="{{subject}}">
				{{subject}}
			</div>
			<div class="date">
					<span class="modified"
						title="{{formatDate dateInt}}">
						{{relativeModifiedDate dateInt}}
					</span>
			</div>
			<div class="icon-delete action delete" title="{{delete}}"></div>
		</div>
	</div>
</script>
<script id="mail-message-template" type="text/x-handlebars-template">
	<div id="mail-message-close" class="icon-close"></div>
	<div id="mail-message-header" class="section">
		<h2 title="{{subject}}">{{subject}}</h2>
		<p class="transparency">
			{{printAddressList fromList}}
			{{#if toList}}
			<?php p($l->t('to')); ?>
			{{printAddressList toList}}
			{{/if}}
			{{#if ccList}}
			(<?php p($l->t('cc')); ?> {{printAddressList ccList}})
			{{/if}}
		</p>
	</div>
	<div class="mail-message-body">
		<div id="mail-content">
			{{#if hasHtmlBody}}
			<div class="icon-loading">
				<iframe src="{{htmlBodyUrl}}" seamless>
				</iframe>
			</div>
			{{else}}
			{{{body}}}
			{{/if}}
		</div>
		{{#if signature}}
		<div class="mail-signature">
			{{{signature}}}
		</div>
		{{/if}}

		<div class="mail-message-attachments">
			{{#if attachment}}
			<ul>
				<li class="mail-message-attachment mail-message-attachment-single" data-message-id="{{attachment.messageId}}" data-attachment-id="{{attachment.id}}" data-attachment-mime="{{attachment.mime}}">
					<img class="attachment-icon" src="{{attachment.mimeUrl}}" />
					{{attachment.fileName}} <span class="attachment-size">({{humanFileSize attachment.size}})</span><br/>
					<a class="button icon-download attachment-download" href="{{attachment.downloadUrl}}"><?php p($l->t('Download attachment')); ?></a>
					<button class="icon-folder attachment-save-to-cloud"><?php p($l->t('Save to Files')); ?></button>
				</li>
			</ul>
			{{/if}}
			{{#if attachments}}
			<ul>
				{{#each attachments}}
				<li class="mail-message-attachment" data-message-id="{{messageId}}" data-attachment-id="{{id}}" data-attachment-mime="{{mime}}">
					<a class="button icon-download attachment-download" href="{{downloadUrl}}" title="<?php p($l->t('Download attachment')); ?>"></a>
					<button class="icon-folder attachment-save-to-cloud" title="<?php p($l->t('Save to Files')); ?>"></button>
					<img class="attachment-icon" src="{{mimeUrl}}" />
					{{fileName}} <span class="attachment-size">({{humanFileSize size}})</span>
				</li>
				{{/each}}
			</ul>
			<p>
				<button data-message-id="{{id}}" class="icon-folder attachments-save-to-cloud"><?php p($l->t('Save all to Files')); ?></button>
			</p>
			{{/if}}
		</div>
		<div id="reply-composer"></div>
		<input type="button" id="forward-button" value="<?php p($l->t('Forward')); ?>">
	</div>
</script>
<script id="mail-attachment-template" type="text/x-handlebars-template">
	<span>{{displayName}}</span><div class="new-message-attachments-action svg icon-delete"></div>
</script>
<script id="mail-settings-template" type="text/x-handlebars-template">
<div id="mailsettings">
	<ul class="mailaccount-list">
		{{#each this}}
		<li id="mail-account-{{accountId}}" data-account-id="{{accountId}}">
			<span class="mail-account-name">{{emailAddress}}</span>
			<span class="actions">
				<a class="icon-delete delete action"
					title="<?php p($l->t('Delete')); ?>"></a>
			</span>
		</li>
		{{/each}}
	</ul>
	<input id="new_mail_account" type="submit" value="<?php p($l->t('Add mail account')); ?>" class="new-button">

	<p class="app-settings-hint"><?php print_unescaped($l->t('Looking to encrypt your emails? Install the <a href="https://www.mailvelope.com/" target="_blank">Mailvelope browser extension</a>!')); ?></p>
</div>
</script>
<script id="mail-composer" type="text/x-handlebars-template">
	<div class="message-composer">
		{{#unless isReply}}
		<select class="mail-account">
			{{#each aliases}}
			<option value="{{accountId}}"><?php p($l->t('from')); ?> {{name}} &lt;{{emailAddress}}&gt;</option>
			{{/each}}
		</select>
		{{/unless}}
		<div class="composer-fields">
			<a href="#" class="composer-cc-bcc-toggle transparency 
                                {{#ifHasCC replyCc replyCcList}}
				hidden
				{{/ifHasCC}}"><?php p($l->t('+ cc/bcc')); ?></a>
			<input type="text" name="to"
                            {{#if replyToList}}
                            value="{{printAddressListPlain replyToList}}"
                            {{else}}
                            value="{{to}}"
                            {{/if}}
                            class="to recipient-autocomplete" />
			<label class="to-label" for="to" class="transparency"><?php p($l->t('to')); ?></label>
			<div class="composer-cc-bcc
                            {{#unlessHasCC replyCc replyCcList}}
                            hidden
                            {{/unlessHasCC}}">
				<input type="text" name="cc"
                                    {{#if replyCc}}
                                    value="{{replyCc}}"
                                    {{else}}
                                        {{#if replyCcList}}
                                        value="{{printAddressListPlain replyCcList}}"
                                        {{else}}
                                        value="{{cc}}"
                                        {{/if}}
                                    {{/if}}
                                    class="cc recipient-autocomplete" />
				<label for="cc" class="cc-label transparency"><?php p($l->t('cc')); ?></label>
				<input type="text" name="bcc" value="{{bcc}}" class="bcc recipient-autocomplete" />
				<label for="bcc" class="bcc-label transparency"><?php p($l->t('bcc')); ?></label>
			</div>
			{{#unless isReply}}
			<input type="text" name="subject" value="{{subject}}" class="subject"
				placeholder="<?php p($l->t('Subject')); ?>" />
			{{/unless}}
			<textarea name="body" class="message-body
						{{#if isReply}} reply{{/if}}"
				placeholder="<?php p($l->t('Message …')); ?>">{{message}}</textarea>
			<input class="submit-message send primary" type="submit" value="{{submitButtonTitle}}" disabled>
		</div>
		<div class="new-message-attachments">
		</div>
	</div>
</script>
<script id="mail-attachments-template" type="text/x-handlebars-template">
	<ul></ul>
	<input type="button" id="mail_new_attachment" value="<?php p($l->t('Add attachment from Files')); ?>">
</script>
<script id="no-search-results-message-list-template" type="text/x-handlebars-template">
	<div id="emptycontent" class="emptycontent-search">
		<div class="icon-search"></div>
		<h2><?php p($l->t('No search results for {{searchTerm}}')); ?></h2>
	</div>
</script>
<script id="message-list-template" type="text/x-handlebars-template">
	<input type="button" id="load-new-mail-messages" value="<?php p($l->t('Check messages …')); ?>">
	<div id="emptycontent" style="display: none;">
		<div class="icon-mail"></div>
		<h2><?php p($l->t('No messages in this folder!')); ?></h2>
	</div>
	<div id="mail-message-list"></div>
	<input type="button" id="load-more-mail-messages" value="<?php p($l->t('Load more …')); ?>">
</script>
<div id="app">
	<div id="app-navigation" class="icon-loading">
		<ul>
			<li id="mail-new-message-fixed">
			<input type="button" id="mail_new_message" class="icon-add"
				value="<?php p($l->t('New message')); ?>" style="display: none">
			</li>
			<li id="mail-new-message-list">
				<div id="folders"></div>
			</li>
		</ul>
		<div id="app-settings">
			<div id="app-settings-header">
				<button class="settings-button"
					data-apps-slide-toggle="#app-settings-content"></button>
			</div>
			<div id="app-settings-content"> </div>
		</div>
	</div>
	<div id="app-content">
		<div id="mail_messages" class="icon-loading">
		</div>



	<form id="mail-setup" class="hidden" method="post">
		<div class="hidden-visually">
			<!-- Hack for Safari and Chromium/Chrome which ignore autocomplete="off" -->
			<input type="text" id="fake_user" name="fake_user"
				autocomplete="off" tabindex="-1">
			<input type="password" id="fake_password" name="fake_password"
				autocomplete="off" tabindex="-1">
		</div>

		<fieldset>
			<div id="emptycontent">
				<div class="icon-mail"></div>
				<h2><?php p($l->t('Connect your mail account')) ?></h2>
			</div>
					<p class="grouptop">
						<input type="text" name="mail-account-name" id="mail-account-name"
							placeholder="<?php p($l->t('Name')); ?>"
							value="<?php p(\OCP\User::getDisplayName(\OCP\User::getUser())); ?>"
							autofocus />
						<label for="mail-address" class="infield"><?php p($l->t('Mail Address')); ?></label>
					</p>
					<p class="groupmiddle">
						<input type="email" name="mail-address" id="mail-address"
							placeholder="<?php p($l->t('Mail Address')); ?>"
							value="<?php p(\OCP\Config::getUserValue(\OCP\User::getUser(), 'settings', 'email', '')); ?>"
							required />
						<label for="mail-address" class="infield"><?php p($l->t('Mail Address')); ?></label>
					</p>
					<p class="groupbottom">
						<input type="password" name="mail-password" id="mail-password"
							placeholder="<?php p($l->t('IMAP Password')); ?>" value=""
							required />
						<label for="mail-password" class="infield"><?php p($l->t('IMAP Password')); ?></label>
					</p>


					<a id="mail-setup-manual-toggle" class="icon-caret-dark"><?php p($l->t('Manual configuration')); ?></a>

					<div id="mail-setup-manual" style="display:none;">
						<p class="grouptop">
							<input type="text" name="mail-imap-host" id="mail-imap-host"
								placeholder="<?php p($l->t('IMAP Host')); ?>"
								value="" />
							<label for="mail-imap-host" class="infield"><?php p($l->t('IMAP Host')); ?></label>
						</p>
						<p class="groupmiddle" id="mail-imap-ssl">
								<label for="mail-imap-sslmode"><?php p($l->t('IMAP security')); ?></label>
								<select name="mail-imap-sslmode" id="mail-imap-sslmode" title="<?php p($l->t('IMAP security')); ?>">
									<option value="none"><?php p($l->t('None')); ?></option>
									<option value="ssl"><?php p($l->t('SSL/TLS')); ?></option>
									<option value="tls"><?php p($l->t('STARTTLS')); ?></option>
								</select>
						</p>
						<p class="groupmiddle">
							<input type="number" name="mail-imap-port" id="mail-imap-port"
								placeholder="<?php p($l->t('IMAP Port')); ?>"
								value="143" />
							<label for="mail-imap-port" class="infield"><?php p($l->t('IMAP Port')); ?></label>
						</p>
						<p class="groupmiddle">
							<input type="text" name="mail-imap-user" id="mail-imap-user"
								placeholder="<?php p($l->t('IMAP User')); ?>"
								value="" />
							<label for="mail-imap-user" class="infield"><?php p($l->t('IMAP User')); ?></label>
						</p>
						<p class="groupbottom">
							<input type="password" name="mail-imap-password" id="mail-imap-password"
								placeholder="<?php p($l->t('IMAP Password')); ?>" value=""
								required />
							<label for="mail-imap-password" class="infield"><?php p($l->t('IMAP Password')); ?></label>
						</p>

						<p class="grouptop">
							<input type="text" name="mail-smtp-host" id="mail-smtp-host"
								placeholder="<?php p($l->t('SMTP Host')); ?>"
								value="" />
							<label for="mail-smtp-host" class="infield"><?php p($l->t('SMTP Host')); ?></label>
						</p>
						<p class="groupmiddle" id="mail-smtp-ssl">
							<label for="mail-smtp-sslmode"><?php p($l->t('SMTP security')); ?></label>
							<select name="mail-smtp-sslmode" id="mail-smtp-sslmode" title="<?php p($l->t('SMTP security')); ?>">
								<option value="none"><?php p($l->t('None')); ?></option>
								<option value="ssl"><?php p($l->t('SSL/TLS')); ?></option>
								<option value="tls"><?php p($l->t('STARTTLS')); ?></option>
							</select>
						</p>
						<p class="groupmiddle">
							<input type="number" name="mail-smtp-port" id="mail-smtp-port"
								placeholder="<?php p($l->t('SMTP Port')); ?>"
								value="587" />
							<label for="mail-smtp-port" class="infield"><?php p($l->t('SMTP Port (default 25, ssl 465)')); ?></label>
						</p>
						<p class="groupmiddle">
							<input type="text" name="mail-smtp-user" id="mail-smtp-user"
								placeholder="<?php p($l->t('SMTP User')); ?>"
								value="" />
							<label for="mail-smtp-user" class="infield"><?php p($l->t('SMTP User')); ?></label>
						</p>
						<p class="groupbottom">
							<input type="password" name="mail-smtp-password" id="mail-smtp-password"
								placeholder="<?php p($l->t('SMTP Password')); ?>" value=""
								required />
							<label for="mail-smtp-password" class="infield"><?php p($l->t('SMTP Password')); ?></label>
						</p>
					</div>


					<img id="connect-loading" src="<?php print_unescaped(OCP\Util::imagePath('core', 'loading.gif')); ?>" style="display:none;" />
					<input type="submit" id="auto_detect_account" class="connect primary" value="<?php p($l->t('Connect')); ?>"/>
				</fieldset>
			</form>



		<div id="mail-message" class="icon-loading hidden-mobile">
		</div>
	</div>

</div>
