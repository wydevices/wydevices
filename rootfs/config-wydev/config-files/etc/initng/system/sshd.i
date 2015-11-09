service system/sshd {
        env_file = /etc/initng/env;
        stdall = ${OUTPUT};
        need = system/initial;
        script start = {
                /usr/sbin/sshd-start
        };
}
