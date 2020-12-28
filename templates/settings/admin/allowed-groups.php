<?php
style('printer', 'admin');
script('printer', 'admin');
?>
<div id="printer_admin">
    <div class="section">
        <h2><?php p($l->t('Limit to groups')) ?></h2>
        <p class="settings-hint">
            <?php p($l->t('When at least one group is selected, only people of the listed groups can print.')); ?>
        </p>

        <ul class="printer-setting" data-name="allowed_groups" data-checkbox>
            <?php foreach ($_['groups'] as $group): ?>
                <li>
                    <input
                        type="checkbox"
                        id="allowed_group_<?php echo $group->getGid() ?>"
                        name="allowed_groups[]"
                        value="<?php p($group->getGid()) ?>"
                        <?php if (in_array($group->getGid(), $_['allowedGroups'])): ?>
                            checked
                        <?php endif ?>
                    >

                    <label for="allowed_group_<?php echo $group->getGid() ?>">
                        <?php p($group->getDisplayName()) ?>
                    </label>
                </li>
            <?php endforeach ?>
        </ul>

        <button id="printer-save" class="btn btn-info">
            <?php p($l->t('Save')); ?>
        </button>

        <span id="printer-message" class="msg"></span>
    </div>
</div>
