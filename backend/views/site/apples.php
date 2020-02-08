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
            <th>Eat</th>
            <th>Eaten</th>
            <th>Drop</th>
        </thead>
        <tbody id="apples-list"></tbody>
    </table>
</div>
<script>
    let AccessToken = <?= json_encode(Yii::$app->user->identity->access_token, JSON_HEX_TAG); ?>;
</script>
