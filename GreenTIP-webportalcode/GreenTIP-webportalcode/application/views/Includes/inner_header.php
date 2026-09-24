<?php $userData = getSessionUserData('auth_user_data');
$userId = $userData['id'];
$points = $this->common_model->getUserPointDetail($userId); ?>
<div class="pageinner-header">
    <div class="graph-paper"></div>
    <div class="container">
        <div class="page-info">
            <div class="item-subtitle"><?php echo isset($this->loggedInUser['name']) ? $this->loggedInUser['name'] . ', ' : 'N/A'; ?>
                You have <?= $points['saveLeads'] ?> saved leads
            </div>
        </div>
        <div class="page-actions">
            <div class="credits-info">
                Remaining Credits <span><?= $points['available'] ?></span>
                <button class="btn" style="color:white;"><i class="ion-ios-plus-outline"></i></button>
            </div>
        </div>
    </div>
</div>