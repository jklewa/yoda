<?php
namespace kcmerrill\yoda;

class docker {
    function stop($container_name) {
        return "docker stop {$container_name}";
    }
    function start($container_name, $silent = true) {
        return "docker start {$container_name} &> /dev/null";
    }
    function kill($container_name) {
        return "docker kill {$container_name}";
    }
    function killall() {
        return 'docker kill $(docker ps -a -q)';
    }
    function pull($image) {
        return "docker pull {$image}";
    }
    function build($image, $dockerfile) {
        return "docker build -t {$image} {$dockerfile}";
    }
    function remove($container_name) {
        return "docker rm -f --volumes {$container_name}";
    }
    function exec($container_name, $command = 'bash') {
        return "docker exec -t -i {$container_name} {$command}";
    }
    function cleanDangling($force = false) {
        return 'docker rmi ' . ($force ? '-f ' : '') . '$(docker images -f "dangling=true" -q)';
    }
    function cleanExited() {
        return 'docker rm ' . ($force ? '-f ' : '') . ' $(docker ps --all -q -f status=exited)';
    }
    function push($image) {
        return 'docker push ' . $image;
    }
    function run($image, $options = array()){
        $options = is_array($options) ? $options : array();
        $run_cmd = array('docker run');
        foreach($options as $c=>$value) {
            /* We need it, but not yet */
            if($c == 'cmd') {
                continue;
            }
            $value = is_array($value) ? $value : array($value);
            foreach($value as $v){
                if(is_bool($v) && $v == false)
                    continue;
                if(strlen($c) == 1) {
                    $run_cmd[] = is_bool($v) ? "-{$c}" : "-{$c} {$v}";
                } else {
                    $run_cmd[] = is_bool($v) ? "--{$c}" : "--{$c}={$v}";
                }
            }
        }
        $run_cmd[] = $image;
        $run_cmd[] = $options['cmd'];
        return implode(' ', $run_cmd);
    }
}
