# .bash_profile

## v4.3.1 :: User specific environment and startup programs

## This makes sure that the file and dir permissions are set properly
umask 0022

## This function allows us to create shell shortcuts
na () {
    if [ -z "$1" ]; then
        echo "alias name:"
        read NAME
    else
        NAME=$1
    fi

    if [ -z "$2" ]; then
        echo "alias definition:"
        read DEFINTION
    else
        if [ "$2" = "-cd" ]; then
            DEFINTION='cd '
        else
            DEFINTION=$2
        fi
    fi

    echo "alias $NAME='$DEFINTION'" >> ~/.bashrc
    . ~/.bashrc
}

## original code
# Get the aliases and functions
if [ -f ~/.bashrc ]; then
    . ~/.bashrc
fi

PATH=$PATH:$HOME/bin

export PATH
unset USERNAME
