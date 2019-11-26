{crmStyle ext=com.listfixer.regpoll file=regpoll.css}
{if isset( $nick_name ) }
<p>Not {$nick_name}?  <a href="/civicrm/regpoll">Click here</a></p>
{/if}
{$description}
<div class="start-button">
{include file="CRM/common/formButtons.tpl" location="bottom"}
</div>