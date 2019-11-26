<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title></title>
</head>
<body>
{if $customPre}
  {foreach from=$customPre item=customValue key=customName}
    {if $customValue eq 'MPRC'}
      {assign var=org value="MPRC"}
    {/if}
  {/foreach}
{/if}

{if $customPost}
  {foreach from=$customPost item=customValue key=customName}
    {if $customValue eq 'MPRC'}
      {assign var=org value="MPRC"}
    {/if}
  {/foreach}
{/if}
{capture assign=headerStyle}colspan="2" style="text-align: left; padding: 4px; border-bottom: 1px solid #999; background-color: #eee;"{/capture}
{capture assign=labelStyle }style="padding: 4px; border-bottom: 1px solid #999; background-color: #f7f7f7;"{/capture}
{capture assign=valueStyle }style="padding: 4px; border-bottom: 1px solid #999;"{/capture}

<center>
  <table width="580" border="0" cellpadding="0" cellspacing="0" id="crm-event_receipt" style="font-family: Arial, Verdana, sans-serif; text-align: left;">

    {if $org eq 'MPRC'}
      <!-- BEGIN MPRC HEADER -->
      <tr><td>
          <table style="margin: 0pt auto; width: 560px; background-color: rgb(153, 153, 204);" border="0" cellpadding="10" cellspacing="0">
            <tbody>
            <tr>
              <td style="font-family: tahoma,arial,helvetica,sans-serif; background-color: rgb(153, 153, 204); width: 150px;">
                <img src="http://www.mainepeoplesresourcecenter.org/images/logomprc.jpg" margin: 5px;" height="160" width="165">

              </td>
              <td style="text-align: center;"><strong><span style="font-size: 16px;"><span style="color: rgb(255, 240, 245);">565 Congress St., Suite 200, Portland, Maine 04101<br>
        Ph: (207) 797-9207;&nbsp; |&nbsp;&nbsp; Fax: (207)797-4716</span></span></strong></td></tr>

            </tbody>
          </table>
        </td>
      </tr>

    {else}
      <!-- BEGIN MPA HEADER -->
      <tr><td>
          <table style="margin: 0pt auto; width: 560px; background-color: rgb(153, 153, 204);" border="0" cellpadding="10" cellspacing="0">
            <tbody>
            <tr>
              <td style="text-align: center; font-family: tahoma,arial,helvetica,sans-serif; background-color: rgb(0, 111, 112);">
                <img src="http://www.mainepeoplesalliance.org/images/mpaheader2.jpg" style="margin: 5px;">

              </td></tr>
            <tr>    <td style="text-align: center; background-color: rgb(0, 111, 112);"><strong><span style="font-size: 16px;"><span style="color: rgb(255, 240, 245);">565 Congress St., Suite 200, Portland, Maine 04101<br>
        Ph: (207) 797-9207;&nbsp; |&nbsp;&nbsp; Fax: (207)797-4716</span></span></strong></td></tr>

            </tbody>
          </table>
        </td>
      </tr>


    {/if}
    <!-- END HEADER -->

    <!-- BEGIN CONTENT -->
    <tr><td align="right"><BR>
        Invoice/Receipt No.{$contributionID}</td></tr>
    <tr><td align="right">
        Contact No.{$contactID}</td></tr>

    <tr>
      <td>

        {if $receipt_text}
          <p>{$receipt_text|htmlize}</p>
        {/if}

        {if $is_pay_later}
          <p>{$pay_later_receipt}</p> {* FIXME: this might be text rather than HTML *}
        {else}
          <p>{ts}Please print this confirmation for your records.{/ts}</p>
        {/if}

      </td>
    </tr>
  </table>
  <table width="580" style="border: 1px solid #999; margin: 1em 0em 1em; border-collapse: collapse;">

    {if $amount}


      <tr>
        <th {$headerStyle}>
          {ts}Contribution Information{/ts}
        </th>
      </tr>

      {if $lineItem and $priceSetID and !$is_quick_config}

        {foreach from=$lineItem item=value key=priceset}
          <tr>
            <td colspan="2" {$valueStyle}>
              <table> {* FIXME: style this table so that it looks like the text version (justification, etc.) *}
                <tr>
                  <th>{ts}Item{/ts}</th>
                  <th>{ts}Qty{/ts}</th>
                  <th>{ts}Each{/ts}</th>
                  {if $dataArray}
                    <th>{ts}Subtotal{/ts}</th>
                    <th>{ts}Tax Rate{/ts}</th>
                    <th>{ts}Tax Amount{/ts}</th>
                  {/if}
                  <th>{ts}Total{/ts}</th>
                </tr>
                {foreach from=$value item=line}
                  <tr>
                    <td>
                      {if $line.html_type eq 'Text'}{$line.label}{else}{$line.field_title} - {$line.label}{/if} {if $line.description}<div>{$line.description|truncate:30:"..."}</div>{/if}
                    </td>
                    <td>
                      {$line.qty}
                    </td>
                    <td>
                      {$line.unit_price|crmMoney:$currency}
                    </td>
                    {if $getTaxDetails}
                      <td>
                        {$line.unit_price*$line.qty|crmMoney:$currency}
                      </td>
                      {if $line.tax_rate != "" || $line.tax_amount != ""}
                        <td>
                          {$line.tax_rate|string_format:"%.2f"}%
                        </td>
                        <td>
                          {$line.tax_amount|crmMoney:$currency}
                        </td>
                      {else}
                        <td></td>
                        <td></td>
                      {/if}
                    {/if}
                    <td>
                      {$line.line_total+$line.tax_amount|crmMoney:$currency}
                    </td>
                  </tr>
                {/foreach}
              </table>
            </td>
          </tr>
        {/foreach}
        {if $dataArray}
          <tr>
            <td {$labelStyle}>
              {ts} Amount before Tax : {/ts}
            </td>
            <td {$valueStyle}>
              {$amount-$totalTaxAmount|crmMoney:$currency}
            </td>
          </tr>

          {foreach from=$dataArray item=value key=priceset}
            <tr>
              {if $priceset || $priceset == 0}
                <td>&nbsp;{$taxTerm} {$priceset|string_format:"%.2f"}%</td>
                <td>&nbsp;{$value|crmMoney:$currency}</td>
              {else}
                <td>&nbsp;{ts}No{/ts} {$taxTerm}</td>
                <td>&nbsp;{$value|crmMoney:$currency}</td>
              {/if}
            </tr>
          {/foreach}

        {/if}
        {if $totalTaxAmount}
          <tr>
            <td {$labelStyle}>
              {ts}Total Tax{/ts}
            </td>
            <td {$valueStyle}>
              {$totalTaxAmount|crmMoney:$currency}
            </td>
          </tr>
        {/if}
        <tr>
          <td {$labelStyle}>
            {ts}Total Amount{/ts}
          </td>
          <td {$valueStyle}>
            {$amount|crmMoney:$currency}
          </td>
        </tr>

      {else}

        {if $totalTaxAmount}
          <tr>
            <td {$labelStyle}>
              {ts}Total Tax Amount{/ts}
            </td>
            <td {$valueStyle}>
              {$totalTaxAmount|crmMoney:$currency}
            </td>
          </tr>
        {/if}
        <tr>
          <td {$labelStyle}>
            {ts}Amount{/ts}
          </td>
          <td {$valueStyle}>
            {$amount|crmMoney:$currency} {if $amount_level} - {$amount_level}{/if}
          </td>
        </tr>

      {/if}

    {/if}


    {if $receive_date}
      <tr>
        <td {$labelStyle}>
          {ts}Date{/ts}
        </td>
        <td {$valueStyle}>
          {$receive_date|crmDate}
        </td>
      </tr>
    {/if}

    {if $is_monetary and $trxn_id}
      <tr>
        <td {$labelStyle}>
          {ts}Transaction #{/ts}
        </td>
        <td {$valueStyle}>
          {$trxn_id}
        </td>
      </tr>
    {/if}

    {if $is_recur}
      {if $contributeMode eq 'notify' or $contributeMode eq 'directIPN'}
       <tr>
        <td  colspan="2" {$labelStyle}>
         {ts 1=$cancelSubscriptionUrl}This is a recurring contribution. You can cancel future contributions by <a href="%1">visiting this web page</a>.{/ts}
        </td>
       <tr>
       </tr>
       </tr>
      {/if}
    {/if}

    {if $honor_block_is_active}
      <tr>
        <th {$headerStyle}>
          {$soft_credit_type}
        </th>
      </tr>
      {foreach from=$honoreeProfile item=value key=label}
        <tr>
          <td {$labelStyle}>
            {$label}
          </td>
          <td {$valueStyle}>
            {$value}
          </td>
        </tr>
      {/foreach}
    {elseif $softCreditTypes and $softCredits}
      {foreach from=$softCreditTypes item=softCreditType key=n}
        <tr>
          <th {$headerStyle}>
            {$softCreditType}
          </th>
        </tr>
        {foreach from=$softCredits.$n item=value key=label}
          <tr>
            <td {$labelStyle}>
              {$label}
            </td>
            <td {$valueStyle}>
              {$value}
            </td>
          </tr>
        {/foreach}
      {/foreach}
    {/if}

    {if $pcpBlock}
      <tr>
        <th {$headerStyle}>
          {ts}Personal Campaign Page{/ts}
        </th>
      </tr>
      <tr>
        <td {$labelStyle}>
          {ts}Display In Honor Roll{/ts}
        </td>
        <td {$valueStyle}>
          {if $pcp_display_in_roll}{ts}Yes{/ts}{else}{ts}No{/ts}{/if}
        </td>
      </tr>
      {if $pcp_roll_nickname}
        <tr>
          <td {$labelStyle}>
            {ts}Nickname{/ts}
          </td>
          <td {$valueStyle}>
            {$pcp_roll_nickname}
          </td>
        </tr>
      {/if}
      {if $pcp_personal_note}
        <tr>
          <td {$labelStyle}>
            {ts}Personal Note{/ts}
          </td>
          <td {$valueStyle}>
            {$pcp_personal_note}
          </td>
        </tr>
      {/if}
    {/if}

    {if $onBehalfProfile}
      <tr>
        <th {$headerStyle}>
          {$onBehalfProfile_grouptitle}
        </th>
      </tr>
      {foreach from=$onBehalfProfile item=onBehalfValue key=onBehalfName}
        <tr>
          <td {$labelStyle}>
            {$onBehalfName}
          </td>
          <td {$valueStyle}>
            {$onBehalfValue}
          </td>
        </tr>
      {/foreach}
    {/if}

    {if $isShare}
      <tr>
        <td colspan="2" {$valueStyle}>
          {capture assign=contributionUrl}{crmURL p='civicrm/contribute/transact' q="reset=1&id=`$contributionPageId`" a=true fe=1 h=1}{/capture}
          {include file="CRM/common/SocialNetwork.tpl" emailMode=true url=$contributionUrl title=$title pageURL=$contributionUrl}
        </td>
      </tr>
    {/if}

    {if ! ($contributeMode eq 'notify' OR $contributeMode eq 'directIPN') and $is_monetary}
      {if $is_pay_later && !$isBillingAddressRequiredForPayLater}
        <tr>
          <th {$headerStyle}>
            {ts}Registered Email{/ts}
          </th>
        </tr>
        <tr>
          <td colspan="2" {$valueStyle}>
            {$email}
          </td>
        </tr>
      {elseif $amount GT 0}
        <tr>
          <th {$headerStyle}>
            {ts}Billing Name and Address{/ts}
          </th>
        </tr>
        <tr>
          <td colspan="2" {$valueStyle}>
            {$billingName}<br />
            {$address|nl2br}<br />
            {$email}
          </td>
        </tr>
      {/if}
    {/if}

    {if $contributeMode eq 'direct' AND !$is_pay_later AND $amount GT 0}
      <tr>
        <th {$headerStyle}>
          {ts}Credit Card Information{/ts}
        </th>
      </tr>
      <tr>
        <td colspan="2" {$valueStyle}>
          {$credit_card_type}<br />
          {$credit_card_number}<br />
          {ts}Expires{/ts}: {$credit_card_exp_date|truncate:7:''|crmDate}<br />
        </td>
      </tr>
    {/if}

    {if $selectPremium}
      <tr>
        <th {$headerStyle}>
          {ts}Premium Information{/ts}
        </th>
      </tr>
      <tr>
        <td colspan="2" {$labelStyle}>
          {$product_name}
        </td>
      </tr>
      {if $option}
        <tr>
          <td {$labelStyle}>
            {ts}Option{/ts}
          </td>
          <td {$valueStyle}>
            {$option}
          </td>
        </tr>
      {/if}
      {if $sku}
        <tr>
          <td {$labelStyle}>
            {ts}SKU{/ts}
          </td>
          <td {$valueStyle}>
            {$sku}
          </td>
        </tr>
      {/if}
      {if $start_date}
        <tr>
          <td {$labelStyle}>
            {ts}Start Date{/ts}
          </td>
          <td {$valueStyle}>
            {$start_date|crmDate}
          </td>
        </tr>
      {/if}
      {if $end_date}
        <tr>
          <td {$labelStyle}>
            {ts}End Date{/ts}
          </td>
          <td {$valueStyle}>
            {$end_date|crmDate}
          </td>
        </tr>
      {/if}
      {if $contact_email OR $contact_phone}
        <tr>
          <td colspan="2" {$valueStyle}>
            <p>{ts}For information about this premium, contact:{/ts}</p>
            {if $contact_email}
              <p>{$contact_email}</p>
            {/if}
            {if $contact_phone}
              <p>{$contact_phone}</p>
            {/if}
          </td>
        </tr>
      {/if}
      {if $is_deductible AND $price}
        <tr>
          <td colspan="2" {$valueStyle}>
            <p>{ts 1=$price|crmMoney:$currency}The value of this premium is %1. This may affect the amount of the tax deduction you can claim. Consult your tax advisor for more information.{/ts}</p>
          </td>
        </tr>
      {/if}
    {/if}
          <!-- Custom Pre  & Post section removed & replaced with hard coded fields-->

          <tr>
            <th {$headerStyle}>
              Contact Information

            </th>
          </tr>
          <tr>
            <td {$labelStyle}>
              First Name
            </td>
            <td {$valueStyle}>
              {contact.first_name}
            </td>
          </tr>
          <tr>
            <td {$labelStyle}>
              Last Name
            </td>
            <td {$valueStyle}>
              {contact.last_name}
            </td>
          </tr>
          <tr>
            <td {$labelStyle}>
              Preferred Name
            </td>
            <td {$valueStyle}>
              {contact.nick_name}
            </td>
          </tr>
          <tr>
            <td {$labelStyle}>
              Street Address (Primary)
            </td>
            <td {$valueStyle}>
              {contact.street_address}
            </td>
          </tr>
          <tr>
            <td {$labelStyle}>
              Suburb (Primary)
            </td>
            <td {$valueStyle}>
              {contact.city}
            </td>
          </tr>
          <tr>
            <td {$labelStyle}>
              State (Primary)
            </td>
            <td {$valueStyle}>
              {contact.state_province}
            </td>
          </tr>
          <tr>
            <td {$labelStyle}>
              Postcode (Primary)
            </td>
            <td {$valueStyle}>
              {contact.postal_code}
            </td>
          </tr>
          <tr>
            <td {$labelStyle}>
              Phone (Primary)
            </td>
            <td {$valueStyle}>
              {contact.phone}
            </td>
          </tr>


        </table>
      </td>
    </tr>

  </table>
</center>

</body>
</html>
