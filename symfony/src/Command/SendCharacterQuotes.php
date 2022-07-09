<?php

namespace App\Command;

use App\Service\Character\DeleteAllCharactersService;
use App\Service\Character\GetCharacterService;
use App\Service\Character\SendCharacterService;
use App\Service\Quote\GetQuoteService;
use App\Service\Quote\SendQuoteService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:send-character-quotes',
    description: 'Send character quotes.',
    hidden: false,
)]
class SendCharacterQuotes extends Command
{
    private GetCharacterService $getCharacterService;
    private GetQuoteService $getQuoteService;
    private SendCharacterService $sendCharacterService;
    private DeleteAllCharactersService $deleteAllCharactersService;
    private SendQuoteService $sendQuoteService;
    private LoggerInterface $logger;

    public function __construct(
        GetCharacterService $getCharacterService,
        GetQuoteService $getQuoteService,
        SendCharacterService $sendCharacterService,
        SendQuoteService $sendQuoteService,
        DeleteAllCharactersService $deleteAllCharactersService,
        LoggerInterface $logger
    )
    {
        $this->getCharacterService = $getCharacterService;
        $this->getQuoteService = $getQuoteService;
        $this->sendQuoteService = $sendQuoteService;
        $this->sendCharacterService = $sendCharacterService;
        $this->deleteAllCharactersService = $deleteAllCharactersService;
        $this->logger = $logger;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('limit', InputArgument::OPTIONAL, 'Total of characters that will be saved.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $characters = $this->getCharacters($input->getArgument('limit') ?: 0);

            if ($characters) {
                $this->sendCharacters($characters);
            }

            $output->writeln(sprintf('[%s] COMMAND SUCCESS: app:send-character-quotes', date('Y-m-d H:i:s')));
        } catch (Exception $e) {
            $this->deleteAllCharactersService->execute();
            $output->writeln(sprintf('[%s] COMMAND FAILED: app:send-character-quotes: %s', date('Y-m-d H:i:s'), $e->getMessage()));
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function getCharacters(int $limit = 0): array
    {
        $characters = $this->getCharacterService->execute();

        $charactersToSave = [];
        $count = 0;
        foreach ($characters as $character) {
            $quotes = $this->getQuoteService->execute($character['firstName']);
            if ($quotes) {
                $quotesToSave = array_map(fn($quote) => $quote['sentence'], is_array($quotes) ? $quotes : [$quotes]);
            }
            $charactersToSave[$character['fullName']] = [
                'name' => $character['fullName'],
                'image_url' => $character['imageUrl'],
                'quotes' => $quotesToSave ?? []
            ];

            $count++;
            if ($limit and $count >= $limit) {
                break;
            }
        }

        return $charactersToSave;
    }

    /**
     * @throws Exception
     */
    private function sendCharacters(array $characters): void
    {
        $savedCharacters = $this->sendCharacterService->execute($characters);
        if (!isset($savedCharacters['data']) or !isset($savedCharacters['data']['insert_Character']['returning'])) {
            throw new Exception($savedCharacters['errors'][0]['message'] ?? 'Failed to send the character');
        }

        foreach ($savedCharacters['data']['insert_Character']['returning'] as $character) {
            $characters[$character['name']]['id'] = $character['id'];
        }

        $savedQuotes = $this->sendQuoteService->execute($characters);

        if (!isset($savedQuotes['data']) or !isset($savedQuotes['data']['insert_Quote']['returning'])) {
            throw new Exception($savedQuotes['errors'][0]['message'] ?? 'Failed to send the character');
        }
    }
}