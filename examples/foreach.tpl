<ul>
{foreach $myColors as $color}
    <li>{$color}</li>
{/foreach}
</ul>

<ul>
{foreach $myPeople as $value}
   <li>{$value@key}: {$value}</li>
{/foreach}
</ul>

<ul>
{foreach from=$myArray item=foo}
    <li>{$foo}</li>
{/foreach}
</ul>

<ul>
{foreach from=$myArray key=k item=v}
   <li>{$k}: {$v}</li>
{/foreach}
</ul>

{foreach name=outer item=contact from=$contacts}
  <hr />
  {foreach key=key item=item from=$contact}
    {$key}: {$item}<br />
  {/foreach}
{/foreach}


{* key always available as a property *}
{foreach $contacts as $contact}
    {$value@key}: {$value}
{/foreach}

{* accessing key the PHP syntax alternate *}
  {foreach $contact as $key => $value}
    {$key}: {$value}
  {/foreach}

{foreach $res as $r} 
  {$r.id} 
  {$r.name}
{foreachelse}
  .. no results .. 
{/foreach}

{* output empty row on the 4th iteration (when index is 3) *}
<table>
{foreach $items as $i}
  {if $i@index eq 3}
     {* put empty table row *}
     <tr><td>nbsp;</td></tr>
  {/if}
  <tr><td>{$i.label}</td></tr>
{/foreach}
</table>

{foreach $myNames as $name}
  {if $name@iteration is div by 4}
    <b>{$name}</b>
  {/if}
  {$name}
{/foreach}

 {foreach $myNames as $name}
   {if $name@iteration is even by 3}
     <span style="color: #000">{$name}</span>
   {else}
     <span style="color: #eee">{$name}</span>
   {/if}
 {/foreach}

 {* show table header at first iteration *}
<table>
{foreach $items as $i}
  {if $i@first}
    <tr>
      <th>key</td>
      <th>name</td>
    </tr>
  {/if}
  <tr>
    <td>{$i@key}</td>
    <td>{$i.name}</td>
  </tr>
{/foreach}
</table>

{* Add horizontal rule at end of list *}
{foreach $items as $item}
  <a href="#{$item.id}">{$item.name}</a>{if $item@last}<hr>{else},{/if}
{foreachelse}
  ... no items to loop ...
{/foreach}

<ul>
{foreach $myArray as $name}
    <li>{$name}</li>
{/foreach}
</ul>
{if $name@show} do something here if the array contained data {/if}


{* show number of rows at end *}
{foreach $items as $item}
  {$item.name}<hr/>
  {if $item@last}
    <div id="total">{$item@total} items</div>
  {/if}
{foreachelse}
 ... no items to loop ...
{/foreach}

  {$data = [1,2,3,4,5]}
  {foreach $data as $value}
    {if $value == 3}
      {* abort iterating the array *}
      {break}
    {/if}
    {$value}
  {/foreach}
  {*
    prints: 1 2
  *}

    {$data = [1,2,3,4,5]}
  {foreach $data as $value}
    {if $value == 3}
      {* skip this iteration *}
      {continue}
    {/if}
    {$value}
  {/foreach}
  {*
    prints: 1 2 4 5
  *}