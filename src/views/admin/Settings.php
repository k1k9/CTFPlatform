<section class="settings">
    <h3 class="red anim-flip">Settings</h3>
    

    <form action="/a/settings" method="POST">
        <div class="element">
            <label for="title">Site name</label>
            <input type="text" name="siteName" 
                placeholder="CTF">
        </div>

        <div class="element">
            <label for="title">Flag prefix</label>
            <input type="text" name="flagPrefix" 
                placeholder="prefix{}">
        </div>

        <fieldset class="radio" required>
                <legend>Restrict access to site</legend>

                <div>
                    <input type="radio" name="restrict" id="restrictYes" value="1">
                    <label for="restrictYes">YES</label>
                </div>

                <div>
                    <input type="radio" name="restrict" id="restrictNo" value="0">
                    <label for="restrictNo">NO</label>
                </div>
            </fieldset>

            <fieldset class="radio" required>
                <legend>Developer mode</legend>

                <div>
                    <input type="radio" name="devmode" id="devmodeYes" value="1">
                    <label for="devmodeYes">YES</label>
                </div>

                <div>
                    <input type="radio" name="devmode" id="devmodeNo" value="0">
                    <label for="devmodeNo">NO</label>
                </div>
            </fieldset>

        <fieldset class="time">
            <legend>Start time</legend>
            <div>
            <label for="startDate">Day</label>
            <input type="date" name="startDate" id="startDate">
            </div>
            <div>
            <label for="startTime">Time</label>
            <input type="time" name="startTime" id="startTime">
            </div>
        </fieldset>

        <input type="submit" value="SAVE">
    </form>
</section>