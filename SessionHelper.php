<?php

namespace SessionHelper;

class SessionHelper
{
    public function read(string $path = null)
    {
        session_start();

        $data = $_SESSION;
        if ($path !== null)
            $data = $this->readArrayRecursive($data, $path);

        // Close to Free the Locked Session File.
        session_write_close();
        return $data;
    }

    /**
     * @param mixed $data
     */
    public function write(string $path, $data): self
    {
        session_start();

        if (is_object($data))
            $data = (array) $data;

        $current_session = $_SESSION;
        $new_session = $this->writeArrayRecursive($current_session, $path, $data);
        $_SESSION = $new_session;

        // Write and Close to Free the Locked Session File.
        session_write_close();
        return $this;
    }

    public function destroy(): self
    {
        session_start();

        // Unset all Session Variables
        session_unset();

        // Destroy the Session
        session_destroy();
        return $this;
    }

    public function readArrayRecursive(array $target, string $path)
    {
        $tree = explode('.', $path);

        $current = &$target;
        foreach($tree as $branch)
            $current = &$current[$branch];
        return $current;
    }

    /**
     * @param mixed $value
     *
     * @link https://stackoverflow.com/questions/13359681/how-to-set-a-deep-array-in-php
     */
    public function writeArrayRecursive(array $target, string $path, $data): array
    {
        $tree = explode('.', $path);

        $current = &$target;
        foreach($tree as $branch)
            $current = &$current[$branch];
        $current = $data;

        return $target;
    }
}
