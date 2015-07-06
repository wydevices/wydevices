service system/extras {
        need = system/initial;
        script start = {
        /wymedia/usr/bin/extras start
        };
}
