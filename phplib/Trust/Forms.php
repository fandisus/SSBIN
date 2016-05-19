<?php
namespace Trust;
class BsHForms {
    static function Text($label, $colSpan, $placeholder, $ngModel, $attributes = "") { ?>
        <div class="form-group form-group-sm">
            <label class="col-sm-<?php echo $colSpan;?> control-label"><?php echo $label; ?></label>
            <div class="col-sm-<?php echo 12-$colSpan;?>">
                <input type="text" class="form-control input-sm" placeholder="<?php echo $placeholder; ?>" ng-model="<?php echo $ngModel; ?>" <?php echo $attributes; ?>/>
            </div>
        </div>
    <?php }
    
    static function Select($label, $colSpan, $placeholder, $ngModel, $ngOptions, $emptyOptionText, $attributes = "") {?>
        <div class="form-group form-group-sm">
            <label class="col-sm-<?php echo $colSpan;?> control-label"><?php echo $label;?></label>
            <div class="col-sm-<?php echo 12-$colSpan;?>">
                <select class="form-control input-sm" ng-model="<?php echo $ngModel; ?>" ng-options="<?php echo $ngOptions; ?>" <?php echo $attributes; ?>>
                    <?php if ($emptyOptionText!="") { ?><option value=""><?php echo $emptyOptionText;?></option><?php } ?>
                </select>
            </div>
        </div>
    <?php }
    
    static function DataList($listId, $label, $colSpan, $placeHolder, $ngModel, $ngRepatArray) { ?>
        <div class="form-group form-group-sm">
            <label class="col-sm-<?php echo $colSpan;?> control-label"><?php echo $label;?></label>
            <div class="col-sm-<?php echo 12-$colSpan;?>">
                <input list="<?php echo $listId; ?>" class="form-control input-sm" ng-model="<?php echo $ngModel; ?>" placeholder="<?php echo $placeHolder; ?>"/>
                <datalist id="<?php echo $listId; ?>">
                    <option ng-repeat="a in <?php echo $ngRepatArray; ?>" value="{{a}}">
                </datalist>
            </div>
        </div>
    <?php }
    
}