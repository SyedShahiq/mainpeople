{ts 1=$displayName}Dear %1{/ts},

{if $recur_txnType eq 'START'}
  {if $auto_renew_membership}
    {ts}Thanks for your auto renew membership sign-up.{/ts}


    {ts 1=$recur_frequency_interval 2=$recur_frequency_unit}This membership will be automatically renewed every %1 %2(s).{/ts}

  {else}
    {ts}On behalf of the staff and board of directors of the Maine People’s Alliance (MPA), your recurring donation is deeply appreciated.{/ts}

    {* fuzion edit - MPRC do not want total installments to show *}
    {ts 1=$recur_frequency_interval 2=$recur_frequency_unit 3=$recur_installments}This recurring contribution will be automatically processed every %1 %2(s) {/ts}
    {* fuzion edit ends *}

    {ts}Start Date{/ts}:  {$recur_start_date|truncate:10:''|crmDate}
  {/if}

  {ts 1=$receipt_from_name 2=$receipt_from_email}These recurring contributions will continue until you contact us to cancel the donation. If you have any questions about your recurring contribution, please contact us at %2.{/ts}

  We value your interest in and support of our goals. Without help from citizens like you, we could not continue to perform the work necessary to ensure our daily democracy in the State of Maine.

  Thank You
{elseif $recur_txnType eq 'END'}
  {if $auto_renew_membership}
    {ts}Your auto renew membership sign-up has ended and your membership will not be automatically renewed.{/ts}


  {else}
    {ts}Your recurring contribution term has ended.{/ts}


    {ts 1=$recur_installments}You have successfully completed %1 recurring contributions. Thank you for your support.{/ts}


    ==================================================
    {ts 1=$recur_installments}Interval of Subscription{/ts}

    ==================================================
    {ts}Start Date{/ts}: {$recur_start_date|truncate:10:''|crmDate}

    {ts}End Date{/ts}: {$recur_end_date|truncate:10:''|crmDate}

  {/if}
{/if}
