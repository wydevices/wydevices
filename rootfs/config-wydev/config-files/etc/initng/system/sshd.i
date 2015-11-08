daemon system/sshd {
        env_file = /etc/initng/env;
        stdall = ${OUTPUT};
        need = system/initial;
        exec daemon = /usr/sbin/sshd-start;
}
