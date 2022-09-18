<div class="col">
   <div class="card">
      <h3 class="card-header">
         <i class="material-icons">person</i>
         Informacje prawne
      </h3>
      <div class="card-body">
         {foreach from=$rows item="row"}
            <div class="row mb-1">
               <div class="col-6 text-right">
                  <strong>{$row.label}</strong>
               </div>
               <div class="col-6">
                  {$row.value}
               </div>
            </div>
         {/foreach}
      </div>
   </div>
</div>
</div>
<div class="row">
