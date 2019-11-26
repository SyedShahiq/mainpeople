{crmStyle ext=com.listfixer.regpoll file=regpoll.css}
<div class="crm-form-block crm-block">
{if $email_status eq "existing"}
<h2>Email Sent</h2>
<p>You should receive an email shortly with a link.  Please open that link to continue.</p>
{/if}
{if $email_status eq "new"}
<h2>Name and Address</h2>
<p>Please enter your name and address address:</p>
<table>
	<tr><td><div class="wizard-label">{$form.first_name.label}</div> {$form.first_name.html}</td></tr>
	<tr><td><div class="wizard-label">{$form.last_name.label}</div> {$form.last_name.html}</td></tr>
	<tr><td><div class="wizard-label">{$form.phone.label}</div> {$form.phone.html}</td></tr>
	<tr><td><div class="wizard-label">{$form.address.label}</div> {$form.address.html}</td></tr>
	<tr><td><div class="wizard-label">{$form.city.label}</div> {$form.city.html}</td></tr>
	<tr><td><div class="wizard-label">State:</div> Maine</td></tr>
	<tr><td><div class="wizard-label">{$form.zip.label}</div> {$form.zip.html}</td></tr>
	<tr><td><div class="buttons-right">{include file="CRM/common/formButtons.tpl" location="bottom"}</div></td></tr>
</table>
{/if}
</div>
