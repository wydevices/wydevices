if {[package vcompare [info tclversion] 8.5] < 0} return

package ifneeded Expect 5.44.1.15 \
    [list load [file join /usr lib libexpect.so.5.44.1.15]]
