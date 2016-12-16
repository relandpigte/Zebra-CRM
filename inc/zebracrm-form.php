<div class="zebracrm-container">
    <div class="msg">
        <span></span>
    </div>
    <form id="zebracrm-form" method="post" action="">
        <p><label>First name:</label>
            <input type="text" name="first_name" placeholder="Please enter you firstname"/>
        </p>
        <p><label>Last name:</label>
            <input type="text" name="last_name" placeholder="Please enter you lastname"/>
        </p>
        <p><label>Email :</label>
            <input type="email" name="email" placeholder="Please enter your email"/>
        </p>
        <p><label>Phone :</label>
            <input type="text" name="phone" placeholder="Please enter your phone"/>
        </p>
        <p><label>City :</label>
            <input type="text" name="city" placeholder="Please enter your city"/>
        </p>
        <p><label>License :</label>
            <select name="license">
                <option value="טרקטור">טרקטור</option>
            </select>
        </p>
        <p>
            <input type="submit" value="Submit ticket">
            <input type="hidden" name="action" value="zebraCRM_submit">
            <?php wp_nonce_field( 'zebracrm_action', 'zebracrm_nonce_field' ); ?>
        </p>
    </form>
</div>
