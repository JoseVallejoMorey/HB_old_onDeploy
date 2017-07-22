<div class="panel panel-default">
    <div class="panel-heading">
        <ul class="nav nav-tabs pull-left" id="tabs">
            Stock
        </ul>
    </div>
    <div class="panel-body">
        <div class="tab-content">

            <form>

            <div class="col-lg-2">
                <select name="stock_seccion">
                    <option value="all">Todo</option>

                    <option value="star_all">Star area Todos</option>
            <?php
                for ($i=1; $i < 11; $i++) { 
                    echo '<option value="star_'.$i.'">Star area '.$i.'</option>';
                }
             ?>        
                    <option value="all">---</option>
                    <option value="special_all">Special Todos</option>
                    <option value="special_alquiler">Special alquiler</option>
                    <option value="special_venta">Special venta</option>
                    <option value="special_comercial">Special comercial</option>
                    <option value="all">---</option>
                    <option value="banners_all">Banners Todos</option>
                    <option value="banners_superior">Banners superior</option>
                    <option value="banners_central">Banners central</option>
                    <option value="banners_lateral">Banners lateral</option>
                    <option value="all">---</option>
                    <option value="promo">Anuncios promocionados</option>
                    <option value="all">---</option>
                    <option value="paquetes">Paquetes</option>
                </select>
                 
            </div>
            <div id="stock-response" class="col-lg-10"></div>

            </form>



        </div>
    </div>
</div>




