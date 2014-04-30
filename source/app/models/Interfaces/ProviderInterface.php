<?php
interface ProviderInterface {
    public function sendMessage(array $collection, array $params);
}