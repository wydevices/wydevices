daemon system/telnet {
        env_file = /etc/initng/env;
        stdall = ${OUTPUT};
        need = system/initial;
        exec daemon = /usr/sbin/sshd-start;
}
