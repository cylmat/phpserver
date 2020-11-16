. ~/git-completion.bash
. ~/git-prompt.sh
export GIT_PS1_SHOWDIRTYSTATE=1
export GIT_PS1_SHOWSTASHSTATE=1
export GIT_PS1_SHOWUNTRACKEDFILES=1
export GIT_PS1_SHOWUPSTREAM=verbose
export GIT_PS1_DESCRIBE_STYLE=branch
#export PS1='${debian_chroot:+($debian_chroot)}\u@\h:\w $(__git_ps1 "(%s)")\$ '

GIT_PS1_SHOWCOLORHINTS=1
#export PROMPT_COMMAND='__git_ps1 "\u@\h:\w" " \\\$ "'
export PROMPT_COMMAND='__git_ps1 "\e[0;36m\u\e[m@\h:\e[0;36m\w\e[m" "\$ "'