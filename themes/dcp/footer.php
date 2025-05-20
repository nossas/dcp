</div>
<?php wp_reset_postdata() ?>
<?php if (is_active_sidebar('footer_widgets')): ?>
    <div class="main-footer-subscribe">
        <?php dynamic_sidebar('footer_widgets_subscribe') ?>
    </div>
    <footer class="main-footer">
        <div class="main-footer__widgets container">
            <?php dynamic_sidebar('footer_widgets') ?>
        </div>

        <div class="main-footer__widgets-mobile container">
            <?php dynamic_sidebar('footer_widgets_mobile') ?>
        </div>
    </footer>
<?php endif; ?>
<?php wp_footer() ?>

</body>

</html>
