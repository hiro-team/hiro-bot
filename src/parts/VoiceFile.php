<?php

/**
 * Copyright 2023 bariscodefx
 * 
 * This file is part of project Hiro 016 Discord Bot.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace hiro\parts;

class VoiceFile
{
    public string $url;
    
    public string $author_id;
    
    public function __construct(string $url, string $author_id)
    {
        $this->setUrl($url);
        $this->setAuthorId($author_id);
    }
    
    public function getUrl(): ?string
    {
        return $this->url;
    }
    
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }
    
    public function getAuthorId(): ?string
    {
        return $this->author_id;
    }
    
    public function setAuthorId(string $author_id): void
    {
        $this->author_id = $author_id;
    }
    
}