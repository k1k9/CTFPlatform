<section class="register">
    <div class="container">
        <h3 class="blue">Sign in</h3>

        <form action="/u/register" method="post">
            <input type="text" placeholder="username" name="username" minlength="4" maxlength="20" require>
            <p>Min 4 and Max 20 characters</p>
            <input type="password" placeholder="password" name="password" minlength="4" require>
            <p>Min 4 characters</p>
            <input type="submit" value="Connect" data-umami-event="Signup button">
        </form>
    </div>
</section>