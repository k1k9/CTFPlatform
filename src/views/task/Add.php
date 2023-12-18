<section class="addTask">
    <form action="/t/add" method="POST">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" placeholder="Example WEB task" required>
        </div>

        <div class="form-group">
            <label for="description" required>Description</label>
            <textarea id="description" name="description" rows="3"></textarea>
        </div>

        <div class="form-group">
            <label for="points">Filename</label>
            <input type="text" id="filename" name="filename" max=100 placeholder="filename on server">
        </div>

        <div class="form-group">
            <label for="flag" required>Flag</label>
            <input type="text" id="flag" name="flag" placeholder="<?= FLAG_PREFIX ?>{}">
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <select id="category" name="category" required>
                <?php foreach ($categories as $category) {
                    echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
                } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="points">Points</label>
            <input type="number" id="points" name="points" min="0" step="10" max=100 placeholder="Enter challenge points" required>
        </div>


        <input type="submit" value="Add Task">
    </form>
</section>