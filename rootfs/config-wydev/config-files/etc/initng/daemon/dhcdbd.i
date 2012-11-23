#!/sbin/itype
# This is a i file, used by initng parsed by install_service

# NAME: HAL
# DESCRIPTION: Demon wrapper dbus/dhcp
# WWW: 


service daemon/dhcdbd/pre-start {
        env_file = /etc/initng/env;
        stdall = ${OUTPUT};
        need = daemon/dbus;
        script start = {
                mkdir -p /var/run;
                mkdir -p /var/lib/dhclient;
				rm -f /etc/ntp.conf;
                touch /var/lib/dhclient/dhclient-vmii0.leases;
                };
}

daemon daemon/dhcdbd {
        env_file = /etc/initng/env;
        stdall = ${OUTPUT};
        need = daemon/dhcdbd/pre-start;
        forks;
        pid_file = /var/run/dhcdbd.pid;
        exec daemon = /sbin/dhcdbd -s;
}
