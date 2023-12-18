<section style="width: 60%;margin: 0 auto;display: flex;flex-direction: column;gap: 3rem;padding-bottom: 2rem;">
    <form action="/a/addCategory" method="POST">
        <div class="form-group">
            <label for="name">Category name</label>
            <input type="text" id="name" name="name" placeholder="Example WEB" required>
        </div>
        <input type="submit" value="Add Task">
    </form>
</section>