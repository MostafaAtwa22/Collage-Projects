-- n_bit to n_bit sign_extend

library ieee;
use ieee.std_logic_1164.all;
entity sign_extend is
    generic(
        IMM_WIDTH : integer := 16;
        EX_IMM_WIDTH : integer := 32
    );
    port(
        imm_const : in std_logic_vector(IMM_WIDTH-1 downto 0) := (others => '0');
        ex_imm_const : out std_logic_vector(EX_IMM_WIDTH-1 downto 0)
    );
end entity;

architecture rtl of sign_extend is
    begin
        process(imm_const) is
            begin
                if(imm_const(IMM_WIDTH-1) = '0') then 
                    ex_imm_const <= (0 to IMM_WIDTH-1 => '0' ) & imm_const;
                else 
                    ex_imm_const <= (0 to IMM_WIDTH-1 => '1' ) & imm_const;
                end if;
            end process;
end architecture;