APPLES
<hr />
<a href="javascript:void(0);" id="createApples">Create apples</a>
<div class="apples">
    <table id="applesTable">
        <thead>
            <th>id</th>
            <th>Color</th>
            <th>Created</th>
            <th>Fall</th>
            <th>Fallen</th>
            <th>State</th>
            <th>Eat it</th>
            <th>Eaten %</th>
            <th>Drop it</th>
        </thead>
        <tbody id="apples-list"></tbody>
    </table>
</div>
<script>
    let AccessToken = '<?= Yii::$app->user->identity->access_token; ?>';
</script>
