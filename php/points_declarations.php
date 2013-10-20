<?php
    define("grad_in_km_x", 0.009016);
    define("grad_in_km_y", 0.015986);

    define("km_in_delta", 2);

    define("delta_x", grad_in_km_x / km_in_delta);
    define("delta_y", grad_in_km_y / km_in_delta);

    define("start_x", 55.914238);
    define("start_y", 37.367587);

    define("end_x", 55.570116);
    define("end_y", 37.848494);

    define("count_x", floor( (start_x - end_x) / delta_x) + 1);
    define("count_y", floor( -(start_y - end_y) / delta_y) + 1);