<div id="kDebug" data-nonce="<?php print $nonce;?>">
    <div class="kDebugToggle"></div>
    <div class="kDebugToggle-content">
        <div class="header">
            <a href="<?php print $editLink;?>">Edit</a>
            &nbsp;|&nbsp;
            <a href="<?php print WP_ADMIN_URL;?>">Admin</a>
        </div>
        <span class="text">
            <?php print $pageName;?>
        </span>    
        <div class="contentHolder">
            <div id="kDebugAccordion">                
                <?php       
                foreach($data as $item){
                    if(!empty($item["data"])){
                        ?> 
                        <div class="accord-header"><?php print $item["title"];?></div>
                        <div class="accord-content"><?php krumo($item["data"]); ?></div>    
                        <?php                        
                    }
                }
                ?>                        
            </div>   
        </div>
        <div class="kDebugToggleFooter">            
        </div>
    </div>    
</div>