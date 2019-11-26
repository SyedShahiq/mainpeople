<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title></title>
</head>
<body>

{capture assign=headerStyle}colspan="2" style="text-align: left; padding: 4px; border-bottom: 1px solid #999; background-color: #eee;"{/capture}
{capture assign=labelStyle }style="padding: 4px; border-bottom: 1px solid #999; background-color: #f7f7f7;"{/capture}
{capture assign=valueStyle }style="padding: 4px; border-bottom: 1px solid #999;"{/capture}

<center>
  <table width="620" border="0" cellpadding="0" cellspacing="0" id="crm-event_receipt" style="font-family: Arial, Verdana, sans-serif; text-align: left;">

    <!-- BEGIN HEADER -->
    <!-- You can add table row(s) here with logo or other header elements -->
    <!-- END HEADER -->

    <!-- BEGIN CONTENT -->

    <tr>
      <td>
        <p>{ts 1=$displayName}Dear %1{/ts},</p>
      </td>
    </tr>

    <tr>
      <td>&nbsp;</td>
    </tr>

    {if $recur_txnType eq 'START'}
      {if $auto_renew_membership}
        <tr>
          <td>
            <p>{ts}Thanks for your auto renew membership sign-up.{/ts}</p>
            <p>{ts 1=$recur_frequency_interval 2=$recur_frequency_unit}This membership will be automatically renewed every %1 %2(s). {/ts}</p>
          </td>
        </tr>
      {else}
        <tr>
          <td>
            <p>{ts}On behalf of the staff and board of directors of the Maine People’s Alliance (MPA), your recurring donation is deeply appreciated.{/ts}</p>
            {* fuzion edit - MPRC do not want total installments to show *}
            <p>{ts 1=$recur_frequency_interval 2=$recur_frequency_unit}Your recurring contribution will be automatically processed every %1 %2(s){/ts}.</p>
            {* fuzion edit ends *}
            <p>{ts}Start Date{/ts}: {$recur_start_date|truncate:10:''|crmDate}</p>
          </td>
        </tr>
      {/if}
      <p>{ts 1=$receipt_from_name 2=$receipt_from_email}These recurring contributions will continue until you contact us to cancel the donation. If you have any questions about your recurring contribution, please contact us at mpa@mainepeoplesalliance.org.{/ts}</p>
      <p>{ts}We value your interest in and support of our goals. Without help from citizens like you, we could not continue to perform the work necessary to ensure our daily democracy in the State of Maine.{/ts}</p>
      <p>{ts}Thanks for your support!{/ts}</p>
    {elseif $recur_txnType eq 'END'}

      {if $auto_renew_membership}
        <tr>
          <td>
            <p>{ts}Your auto renew membership sign-up has ended and your membership will not be automatically renewed.{/ts}</p>
          </td>
        </tr>
      {else}
        <tr>
          <td>
            <p>{ts}Your recurring contribution term has ended.{/ts}</p>
            <p>{ts 1=$recur_installments}You have successfully completed %1 recurring contributions. Thank you for your support.{/ts}</p>
          </td>
        </tr>
        <tr>
          <td>
            <table style="border: 1px solid #999; margin: 1em 0em 1em; border-collapse: collapse; width:100%;">
              <tr>
                <th {$headerStyle}>
                  {ts 1=$recur_installments}Interval of Subscription{/ts}
                </th>
              </tr>
              <tr>
                <td {$labelStyle}>
                  {ts}Start Date{/ts}
                </td>
                <td {$valueStyle}>
                  {$recur_start_date|crmDate}
                </td>
              </tr>
              <tr>
                <td {$labelStyle}>
                  {ts}End Date{/ts}
                </td>
                <td {$valueStyle}>
                  {$recur_end_date|crmDate}
                </td>
              </tr>
            </table>
          </td>
        </tr>

      {/if}
    {/if}

  </table>
</center>

</body>
</html>
