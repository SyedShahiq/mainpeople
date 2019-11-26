{crmStyle ext=com.listfixer.regpoll file=regpoll.css}
{$description}
<div class="crm-form-block crm-block">
<h2>Step 1:  Select County</h2>
<p>In which county do you want to volunteer?</p>
<table class=wizard>
	<tr><td>{$form.county.html}</td></tr>
	<tr><td><div class="buttons-right">{include file="CRM/common/formButtons.tpl" location="bottom"}</div></td></tr>
</table>
</div>
